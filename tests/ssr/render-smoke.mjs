/**
 * SSR render smoke test.
 *
 * Boots the built SSR bundle and renders each committed fixture, asserting that
 * a real body comes back and that the server logged nothing to stderr.
 *
 * Why this exists rather than a status-code check: Inertia's SSR server
 * (@inertiajs/core/dist/server.js) writes its 200 status line *before* awaiting
 * the render, swallows any throw to console.error, then calls response.end().
 * A failed render is therefore indistinguishable from a successful one at the
 * HTTP layer -- it returns 200 with a zero-byte body. The response body and the
 * process's stderr are the only signals available, so both are asserted here.
 *
 * Deliberately dependency-free: plain Node, no test runner. Node 22 provides
 * global fetch; the only import is node:child_process.
 *
 * Usage: pnpm test:ssr
 */

import { spawn } from 'node:child_process';
import { existsSync, readdirSync, readFileSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const REPO_ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '../..');
const BUNDLE = join(REPO_ROOT, 'bootstrap/ssr/ssr.js');
const FIXTURE_DIR = join(REPO_ROOT, 'tests/ssr/fixtures');
const PORT = 13714;
const BASE = `http://127.0.0.1:${PORT}`;

/**
 * Crude truncation floor; the data-page marker below is the real signal. Kept
 * well under the smallest observed render (post.json at ~11.9 KB, vs home.json
 * at ~209 KB) so a short post can never trip it. A broken render returns 0
 * bytes, so anything in this range is comfortably sufficient to catch it.
 */
const MIN_BODY_BYTES = 5_000;
/** Only catches a true hang; render latency is reported, not gated -- a tight
 *  budget on a shared CI runner would flake, and flaky gates get disabled. */
const RENDER_TIMEOUT_MS = 10_000;
const HEALTH_TIMEOUT_MS = 30_000;

const failures = [];
const fail = (msg) => failures.push(msg);

function fixtures() {
    if (!existsSync(FIXTURE_DIR)) {
        throw new Error(`No fixture directory at ${FIXTURE_DIR}`);
    }

    const files = readdirSync(FIXTURE_DIR)
        .filter((f) => f.endsWith('.json'))
        .sort();

    if (files.length === 0) {
        throw new Error(`No .json fixtures in ${FIXTURE_DIR}`);
    }

    return files.map((file) => ({
        file,
        page: JSON.parse(readFileSync(join(FIXTURE_DIR, file), 'utf8')),
    }));
}

async function waitForHealth(deadline) {
    while (Date.now() < deadline) {
        try {
            const res = await fetch(`${BASE}/health`, { signal: AbortSignal.timeout(2000) });
            if (res.ok) return true;
        } catch {
            // Not listening yet.
        }
        await new Promise((r) => setTimeout(r, 250));
    }
    return false;
}

async function renderFixture({ file, page }) {
    const started = Date.now();

    let res;
    try {
        res = await fetch(`${BASE}/render`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(page),
            signal: AbortSignal.timeout(RENDER_TIMEOUT_MS),
        });
    } catch (e) {
        fail(`${file}: render request failed or hung after ${RENDER_TIMEOUT_MS}ms (${e.name}: ${e.message})`);
        return;
    }

    const elapsed = Date.now() - started;
    const raw = await res.text();

    if (res.status !== 200) {
        fail(`${file}: expected HTTP 200, got ${res.status}`);
        return;
    }

    // An empty body here IS the bug: 200 with zero bytes is how a thrown render
    // surfaces. Report it explicitly rather than as a JSON parse error.
    if (raw.length === 0) {
        fail(`${file}: HTTP 200 with a ZERO-BYTE body -- the render threw. Check stderr below.`);
        return;
    }

    let payload;
    try {
        payload = JSON.parse(raw);
    } catch {
        fail(`${file}: response was not valid JSON (${raw.length} bytes): ${raw.slice(0, 200)}`);
        return;
    }

    const body = payload.body ?? '';
    const head = payload.head ?? [];

    if (body.length < MIN_BODY_BYTES) {
        fail(`${file}: body is ${body.length} bytes, below the ${MIN_BODY_BYTES} floor (truncated render?)`);
    }

    if (!body.includes('<div id="app" data-page=')) {
        fail(`${file}: body lacks the server-rendered '<div id="app" data-page=' marker`);
    }

    if (head.length === 0) {
        fail(`${file}: head is empty -- title and meta tags were not rendered`);
    }

    const status = failures.length === 0 ? 'ok' : 'see failures';
    console.log(
        `  ${file.padEnd(14)} ${String(body.length).padStart(7)} bytes  ${String(elapsed).padStart(5)} ms  head:${head.length}  ${status}`,
    );
}

async function main() {
    if (!existsSync(BUNDLE)) {
        console.error(`SSR bundle missing at ${BUNDLE}\nRun: pnpm run build:ssr`);
        process.exit(1);
    }

    const cases = fixtures();
    console.log(`SSR render smoke test -- ${cases.length} fixture(s)\n`);

    const server = spawn('node', [BUNDLE], { cwd: REPO_ROOT, stdio: ['ignore', 'pipe', 'pipe'] });
    let stdout = '';
    let stderr = '';
    server.stdout.on('data', (d) => (stdout += d));
    server.stderr.on('data', (d) => (stderr += d));

    let exited = null;
    server.on('exit', (code, signal) => (exited = signal ?? code));

    try {
        if (!(await waitForHealth(Date.now() + HEALTH_TIMEOUT_MS))) {
            fail(`SSR server never became healthy on ${BASE} within ${HEALTH_TIMEOUT_MS}ms (exit: ${exited})`);
        } else {
            for (const c of cases) {
                await renderFixture(c);
            }
        }
    } finally {
        try {
            await fetch(`${BASE}/shutdown`, { signal: AbortSignal.timeout(2000) });
        } catch {
            // /shutdown exits the process mid-response, so a transport error is expected.
        }
        // Give it a moment to flush and die before falling back to a signal.
        await new Promise((r) => setTimeout(r, 300));
        if (exited === null) server.kill('SIGKILL');
    }

    // The load-bearing assertion. Upstream swallows render errors to
    // console.error, so a clean body is not sufficient evidence of a clean render.
    if (stderr.trim().length > 0) {
        fail('SSR server wrote to stderr -- a render threw even if a body came back');
        console.error(`\n--- SSR stderr ---\n${stderr.trim()}\n------------------`);
    }

    if (failures.length > 0) {
        console.error(`\nFAILED (${failures.length}):`);
        for (const f of failures) console.error(`  - ${f}`);
        if (stdout.trim()) console.error(`\n--- SSR stdout ---\n${stdout.trim()}\n------------------`);
        process.exit(1);
    }

    console.log('\nPASS -- every fixture rendered a real body with clean stderr.');
}

main().catch((e) => {
    console.error(`render-smoke crashed: ${e.stack ?? e.message}`);
    process.exit(1);
});
