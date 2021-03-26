<tr wire:poll.180s="refresh">
    @if ($project->status === \App\Constants\Status::DOWN)
        <x-table.body-heading class="bg-red-50 whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-red-700 w-3">@svg('icon-status-down')</div>
                <div class="font-medium text-red-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::WARN)
        <x-table.body-heading class="bg-yellow-50 whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-yellow-500 w-3">@svg('icon-status-warn')</div>
                <div class="font-medium text-yellow-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::OKAY)
        <x-table.body-heading class="whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-gray-300 w-3">@svg('icon-status-okay')</div>
                <div class="font-medium">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_count', $project->components->count()) }}</x-table.body-cell>
    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_warnings', $project->components()->warn()->count()) }}</x-table.body-cell>
    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_errors', $project->components()->down()->count()) }}</x-table.body-cell>

    <x-table.body-cell class="text-right">
        <x-jet-dropdown>
            <x-slot name="trigger">
                <x-dropdown-button aria-expanded="true" aria-haspopup="true" id="options-menu">
                    {{ __('Actions') }}
                </x-dropdown-button>
            </x-slot>

            <x-slot name="content">
                <div aria-labelledby="options-menu" aria-orientation="vertical" role="menu">
                    <div class="py-1" role="none">
                        <a
                            class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                            href="{{ route('projects.edit', ['project' => $project]) }}"
                            role="menuitem"
                        >
                            <div class="text-gray-400 w-4 group-hover:text-gray-500">@svg('icon-edit')</div>
                            <div class="ml-4">{{ __('Edit') }}</div>
                        </a>
                    </div>

                    <div class="py-1" role="none">
                        <button
                            class="group flex items-center px-4 py-2 text-sm text-gray-700 w-full hover:bg-gray-100 hover:text-gray-900"
                            role="menuitem"
                            @click.prevent="open = false"
                            wire:click="$toggle('confirmingProjectDeletion')"
                            wire:loading.attr="disabled"
                        >
                            <div class="text-gray-400 w-4 group-hover:text-gray-500">@svg('icon-delete')</div>
                            <div class="ml-4">{{ __('Delete') }}</div>
                        </button>
                    </div>
                </div>
            </x-slot>
        </x-jet-dropdown>

        <x-jet-confirmation-modal :id="$project->uuid" wire:model="confirmingProjectDeletion">
            <x-slot name="title">{{ __('Delete Project') }}</x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this project? This cannot be undone.') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingProjectDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteProject" wire:loading.attr="disabled">
                    {{ __('Delete Project') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-table.body-cell>
</tr>
