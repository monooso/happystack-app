<x-base-layout>
    <x-jet-banner/>

    <header>
        <div class="mb-8">
            <livewire:navigation-menu/>
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
</x-base-layout>
