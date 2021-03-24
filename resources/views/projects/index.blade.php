<x-app-layout>
    <div>
        @if (count($projects) === 0)
            <div class="flex h-64 items-center justify-center w-full">
                <form action="{{ route('projects.create') }}">
                    <x-jet-button class="shadow-md">{{ __('Create your first project') }}</x-jet-button>
                </form>
            </div>
        @endif

        @if (count($projects) > 0)
            <div class="mb-6 text-right">
                <form action="{{ route('projects.create') }}">
                    <x-jet-button class="text-sm">{{ __('Create project') }}</x-jet-button>
                </form>
            </div>

            <x-table.table>
                <x-table.head>
                    <x-table.row>
                        <x-table.head-heading>{{ __('Project') }}</x-table.head-heading>
                        <x-table.head-heading>{{ __('Monitoring') }}</x-table.head-heading>
                        <x-table.head-heading>{{ __('Warnings') }}</x-table.head-heading>
                        <x-table.head-heading>{{ __('Errors') }}</x-table.head-heading>
                    </x-table.row>
                </x-table.head>

                <x-table.body>
                @foreach ($projects as $project)
                    @livewire('projects.project-status', ['project' => $project])
                @endforeach
                </x-table.body>
            </x-table.table>
            @endif
    </div>
</x-app-layout>
