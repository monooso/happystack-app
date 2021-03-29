<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles

    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="bg-gray-100 border-t-4 border-yellow-500 font-sans antialiased">
<x-jet-banner/>

<header>
    <div class="mb-8">
        <livewire:navigation-menu />
    </div>

    @if (isset($header))
        <x-container>
            <div class="mb-12 px-4 sm:px-0">
                <h1 class="font-semibold leading-7 text-3xl text-gray-900">{{ $header }}</h1>
            </div>
        </x-container>
    @endif
</header>

<main class="pb-12">
    <x-container>{{ $slot }}</x-container>
</main>

@stack('modals')
@livewireScripts
</body>
</html>
