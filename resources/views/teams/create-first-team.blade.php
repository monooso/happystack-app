<x-squeeze-layout>
    <x-blank-slate>
        <x-slot name="title">{{ __('Create your first team') }}</x-slot>

        <x-slot name="description">
            <p>Once you’ve created a team you can add client projects, and invite your co-workers to join.</p>
        </x-slot>

        <x-slot name="action">
            <livewire:create-first-team-form />
        </x-slot>
    </x-blank-slate>
</x-squeeze-layout>
