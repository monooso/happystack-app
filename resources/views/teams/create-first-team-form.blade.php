<form wire:submit.prevent="createTeam">
    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="text-left">
                <x-label for="name" value="{{ __('Team name') }}" />
                <x-input class="mt-1 block w-full" id="name" type="text" autofocus wire:model.defer="state.name" />
                <x-input-error for="name" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end mt-8 px-4 sm:px-0">
        <x-link class="mr-8" href="{{ route('teams.join') }}">{{ __('Invited to join a team?') }}</x-link>
        <x-button>Create team</x-button>
    </div>
</form>
