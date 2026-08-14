<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0f172a">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="Jon Russell is a full stack software engineer who creates robust, scalable solutions that power modern web and mobile applications.">

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

        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" defer></script>

        @vite(['resources/js/app.ts'])
        @inertiaHead

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z1V3TF6W15"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-Z1V3TF6W15');
        </script>
    </head>
    <body class="font-sans scroll-smooth antialiased">
        <div class="isolate">
            @inertia
        </div>
    </body>
</html>
