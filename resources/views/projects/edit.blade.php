<x-app-layout>
    <x-slot name="header">{{ __('Edit Project') }}</x-slot>
    <livewire:projects.create-update-form :project="$project" />
</x-app-layout>
