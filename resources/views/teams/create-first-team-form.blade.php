<form wire:submit.prevent="createTeam">
    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="text-left">
                <x-jet-label for="name" value="{{ __('Team name') }}" />
                <x-jet-input class="mt-1 block w-full" id="name" type="text" autofocus wire:model.defer="state.name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end mt-8 px-4 sm:px-0">
        <x-link class="mr-8" href="{{ route('teams.join') }}">{{ __('Invited to join a team?') }}</x-link>
        <x-jet-button>Create team</x-jet-button>
    </div>
</form>
