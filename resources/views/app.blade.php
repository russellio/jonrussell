<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0f172a">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Jon Russell - Senior Full Stack Engineer') }}</title>

        <meta property="og:locale" content="en_US">
        <meta property="og:site_name" content="Jon Russell">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Jon Russell">
        <meta property="og:description" content="A full stack software engineer who creates robust, scalable solutions that power modern web applications.">
        <meta property="og:url" content="{{ url()->current() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
        <link rel="manifest" href="/favicons/site.webmanifest">

        {{-- Latin faces used above the fold. Preloading lets them arrive with the CSS instead of after it, so the swap from the fallback lands before first paint (CLS). The Noto Sans file is the variable wght build, so one preload covers every weight the design uses. --}}
        @foreach ([
            '@fontsource-variable/noto-sans/files/noto-sans-latin-wght-normal.woff2',
            '@fontsource/space-mono/files/space-mono-latin-400-normal.woff2',
            '@fontsource/space-mono/files/space-mono-latin-700-normal.woff2',
            '@fontsource/sixtyfour/files/sixtyfour-latin-400-normal.woff2',
        ] as $fontPath)
            @if ($fontUrl = Vite::packageAsset($fontPath))
                <link rel="preload" href="{{ $fontUrl }}" as="font" type="font/woff2" crossorigin>
            @endif
        @endforeach

        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans scroll-smooth antialiased">
        <div class="isolate">
            @inertia
        </div>
    </body>
</html>
