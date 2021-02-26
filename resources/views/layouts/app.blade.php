<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

@livewireStyles

<!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="bg-gray-100 border-t-4 border-yellow-500 font-sans antialiased">
<x-jet-banner/>

<header>
    <div class="mb-8">
        @livewire('navigation-menu')
    </div>

    <x-container>
        <div class="px-4 sm:px-0">
            <h1 class="font-bold leading-7 text-3xl text-gray-900">{{ $header }}</h1>
        </div>
    </x-container>
</header>

<main class="py-12">
    <x-container>{{ $slot }}</x-container>
</main>

@stack('modals')
@livewireScripts
</body>
</html>
