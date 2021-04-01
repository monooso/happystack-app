<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon hell -->
    <link rel="icon" href="/favicon.ico">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon-apple-touch.png">
    <link rel="manifest" href="/manifest.webmanifest">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles

    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="antialiased bg-gray-200 border-t-4 border-yellow-500 font-sans text-gray-900">
    <x-jet-banner />

    @if (isset($header))
    <header>{{ $header }}</header>
    @endif

    @if (isset($main))
    <main>{{ $main }}</main>
    @endif

    @livewireScripts
</body>

</html>
