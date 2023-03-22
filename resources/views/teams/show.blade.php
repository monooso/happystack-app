<x-app-layout>
    <x-slot name="header">{{ __('Team Settings') }}</x-slot>

    <livewire:teams.update-team-name-form :team="$team" />
    <livewire:teams.team-member-manager :team="$team" />

    @if (Gate::check('delete', $team) && ! $team->personal_team)
        <x-section-border/>
        <livewire:teams.delete-team-form :team="$team" />
    @endif
</x-app-layout>
