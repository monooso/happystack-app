<x-squeeze-layout>
    <x-blank-slate>
        <x-slot name="title">{{ __('Join an existing team') }}</x-slot>

        <x-slot name="action">
            <div class="text-left text-lg">
                <x-card>
                    <x-sleeve>
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
                    </x-sleeve>
                </x-card>
            </div>
        </x-slot>
    </x-blank-slate>
</x-squeeze-layout>
