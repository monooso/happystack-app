<x-app-layout>
    <x-slot name="header">{{ __('Create Project') }}</x-slot>
    <livewire:projects.create-update-form :project="$project" />
</x-app-layout>
