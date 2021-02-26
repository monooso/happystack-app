<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-24 text-center w-full">
        <a href="/projects/new">{{ __('Create your first project') }}</a>
    </div>
</x-app-layout>
