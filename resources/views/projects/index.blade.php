<x-app-layout>
    @if (count($projects) === 0)
        <x-blank-slate>
            <x-slot name="title">{{ __('Create your first project') }}</x-slot>

            <x-slot name="description">
                <p>Use a project to monitor all of the services related to a single website or application.</p>
            </x-slot>

            <x-slot name="action">
                <form action="{{ route('projects.create') }}">
                    <x-jet-button class="shadow-md">{{ __('Let’s get started') }}</x-jet-button>
                </form>
            </x-slot>
        </x-blank-slate>
    @endif

    @if (count($projects) > 0)
        <x-slot name="header">{{ __('Projects') }}</x-slot>

        <div class="mb-8 text-center sm:text-right">
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
                    <x-table.head-heading><span class="sr-only">{{ __('Actions') }}</span></x-table.head-heading>
                </x-table.row>
            </x-table.head>

            <x-table.body>
                @foreach ($projects as $project)
                    <livewire:projects.project-status :project="$project"/>
                @endforeach
            </x-table.body>
        </x-table.table>
    @endif
</x-app-layout>
