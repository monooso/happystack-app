<x-base-layout>
    <x-slot name="header">
        <x-top-bar.squeeze/>
    </x-slot>

    <x-slot name="main">
        <div class="my-16">
            <x-container>{{ $slot }}</x-container>
        </div>
    </x-slot>

    @stack('modals')
</x-base-layout>
