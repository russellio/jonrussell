<?php

use Illuminate\Foundation\ViteException;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

/**
 * The package-relative paths app.blade.php preloads, in order.
 *
 * @return list<string>
 */
function preloadedFonts(): array
{
    return [
        '@fontsource-variable/noto-sans/files/noto-sans-latin-wght-normal.woff2',
        '@fontsource/space-mono/files/space-mono-latin-400-normal.woff2',
        '@fontsource/space-mono/files/space-mono-latin-700-normal.woff2',
        '@fontsource/sixtyfour/files/sixtyfour-latin-400-normal.woff2',
    ];
}

/**
 * Point the app at a throwaway public dir holding a Vite manifest whose font entries are keyed the
 * way pnpm makes Vite key them: by realpath, with the package version baked in.
 *
 * @param  list<string>  $fonts  package-relative paths, e.g. `@fontsource/sixtyfour/files/x.woff2`
 */
function useBuildWithFonts(array $fonts): string
{
    $publicPath = storage_path('framework/testing/vite/'.Str::random(8));

    $manifest = ['resources/js/app.ts' => ['file' => 'assets/app-abc123.js', 'src' => 'resources/js/app.ts', 'isEntry' => true]];

    foreach ($fonts as $font) {
        [$scope, $package] = explode('/', $font);
        $realpathDir = "node_modules/.pnpm/{$scope}+{$package}@5.3.0/node_modules";

        $manifest["{$realpathDir}/{$font}"] = ['file' => 'assets/'.hashedFontFile($font)];
    }

    File::ensureDirectoryExists("{$publicPath}/build");
    File::put("{$publicPath}/build/manifest.json", json_encode($manifest));
    app()->usePublicPath($publicPath);

    return $publicPath;
}

/**
 * The built filename Vite would emit for a font, standing in for the content hash.
 */
function hashedFontFile(string $font): string
{
    return str_replace('.woff2', '-HASH.woff2', basename($font));
}

afterEach(function () {
    File::deleteDirectory(storage_path('framework/testing/vite'));
});

it('preloads each latin font from a version-keyed manifest', function () {
    useBuildWithFonts(preloadedFonts());

    $response = $this->get('/');

    foreach (preloadedFonts() as $font) {
        $url = url('build/assets/'.hashedFontFile($font));
        $response->assertSee('<link rel="preload" href="'.$url.'" as="font" type="font/woff2" crossorigin>', false);
    }
});

it('still renders the page and reports when a font is missing from the manifest', function () {
    Exceptions::fake();
    $present = array_slice(preloadedFonts(), 0, -1);
    useBuildWithFonts($present);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('noto-sans-latin-wght-normal-HASH.woff2', false)
        ->assertDontSee('sixtyfour-latin-400-normal', false);
    Exceptions::assertReported(fn (ViteException $e): bool => str_contains($e->getMessage(), 'sixtyfour-latin-400-normal.woff2'));
});

it('does not preload fonts while Vite is running hot', function () {
    $publicPath = useBuildWithFonts(preloadedFonts());
    File::put("{$publicPath}/hot", 'http://localhost:5173');
    Vite::useHotFile("{$publicPath}/hot");

    $response = $this->get('/');

    $response->assertOk()->assertDontSee('as="font"', false);
});
