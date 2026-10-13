<?php

use Illuminate\Foundation\ViteException;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

/**
 * Point the app at a throwaway public dir holding a Vite manifest whose font entries are keyed the
 * way pnpm makes Vite key them: by realpath, with the package version baked in.
 *
 * @param  list<string>  $fonts
 */
function useBuildWithFonts(array $fonts): string
{
    $publicPath = storage_path('framework/testing/vite/'.Str::random(8));

    $manifest = ['resources/js/app.ts' => ['file' => 'assets/app-abc123.js', 'src' => 'resources/js/app.ts', 'isEntry' => true]];

    foreach ($fonts as $font) {
        $manifest["node_modules/.pnpm/@fontsource+{$font}@5.3.0/node_modules/@fontsource/{$font}/files/{$font}-latin-400-normal.woff2"] = [
            'file' => "assets/{$font}-latin-400-normal-HASH.woff2",
        ];
    }

    File::ensureDirectoryExists("{$publicPath}/build");
    File::put("{$publicPath}/build/manifest.json", json_encode($manifest));
    app()->usePublicPath($publicPath);

    return $publicPath;
}

afterEach(function () {
    File::deleteDirectory(storage_path('framework/testing/vite'));
});

it('preloads each latin font from a version-keyed manifest', function () {
    useBuildWithFonts(['noto-sans', 'space-mono', 'sixtyfour']);

    $response = $this->get('/');

    $response->assertSee('<link rel="preload" href="'.url('build/assets/noto-sans-latin-400-normal-HASH.woff2').'" as="font" type="font/woff2" crossorigin>', false)
        ->assertSee('<link rel="preload" href="'.url('build/assets/space-mono-latin-400-normal-HASH.woff2').'" as="font" type="font/woff2" crossorigin>', false)
        ->assertSee('<link rel="preload" href="'.url('build/assets/sixtyfour-latin-400-normal-HASH.woff2').'" as="font" type="font/woff2" crossorigin>', false);
});

it('still renders the page and reports when a font is missing from the manifest', function () {
    Exceptions::fake();
    useBuildWithFonts(['noto-sans', 'space-mono']);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('noto-sans-latin-400-normal-HASH.woff2', false)
        ->assertDontSee('sixtyfour-latin-400-normal', false);
    Exceptions::assertReported(fn (ViteException $e): bool => str_contains($e->getMessage(), 'sixtyfour-latin-400-normal.woff2'));
});

it('does not preload fonts while Vite is running hot', function () {
    $publicPath = useBuildWithFonts(['noto-sans', 'space-mono', 'sixtyfour']);
    File::put("{$publicPath}/hot", 'http://localhost:5173');
    Vite::useHotFile("{$publicPath}/hot");

    $response = $this->get('/');

    $response->assertOk()->assertDontSee('as="font"', false);
});
