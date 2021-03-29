<x-squeeze-layout>
    <x-slot name="header">{{ __('Join an existing team') }}</x-slot>

    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="text-left">
                <p>To join a team, <strong>click the “Accept Invitation” button in the invitation email</strong>.</p>
                <p class="mt-4">
                    If you didn’t receive an invitation email, you’ll need to ask the team owner to send you one.
                    Or perhaps you’d prefer to
                    @if (user()->belongsToATeam())
                        <x-link href="{{ route('teams.create') }}">create your own team</x-link>.
                    @else
                        <x-link href="{{ route('teams.create-first') }}">create your own team</x-link>.
                    @endif
                </p>
            </div>
        </div>
    </div>
</x-squeeze-layout>
