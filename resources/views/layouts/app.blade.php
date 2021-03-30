<x-base-layout>
    <x-slot name="header">
        <x-top-bar.full />

        @if(isset($header))
            <div class="bg-gray-50 py-6 shadow">
                <x-container>
                    <h1 class="font-bold text-2xl">{{ $header }}</h1>
                </x-container>
            </div>
        @endif
    </x-slot>

    <x-slot name="main">
        <div class="my-16">
            <x-container>{{ $slot }}</x-container>
        </div>
    </x-slot>

    @stack('modals')
</x-base-layout>
