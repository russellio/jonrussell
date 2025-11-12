<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') === 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
        <link rel="manifest" href="/favicons/site.webmanifest">

        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" defer></script>

        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z1V3TF6W15"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-Z1V3TF6W15');
    </script>
    <body class="font-sans scroll-smooth antialiased">
        <div class="isolate">
            @inertia
        </div>
    </body>
</html>
