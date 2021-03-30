<x-app-layout>
    <x-slot name="header">{{ __('Create project') }}</x-slot>
    <livewire:projects.create-update-form :project="$project" />
</x-app-layout>
