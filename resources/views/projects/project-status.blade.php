<tr wire:poll.180s="refresh">
    @if ($project->status === \App\Constants\Status::DOWN)
        <x-table.body-heading class="bg-red-50 whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-red-700 w-3">
                    <x-zondicon-hand-stop />
                </div>
                <div class="font-semibold text-red-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::WARN)
        <x-table.body-heading class="bg-yellow-50 whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-yellow-500 w-3">
                    <x-zondicon-information-outline />
                </div>
                <div class="font-semibold text-yellow-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::OKAY)
        <x-table.body-heading class="whitespace-nowrap">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-gray-300 w-3">
                    <x-zondicon-checkmark />
                </div>
                <div class="font-semibold">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_count', $project->components->count()) }}</x-table.body-cell>
    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_warnings', $project->components()->warn()->count()) }}</x-table.body-cell>
    <x-table.body-cell class="whitespace-nowrap">{{ trans_choice('app.component_errors', $project->components()->down()->count()) }}</x-table.body-cell>

    <x-table.body-cell class="text-right">
        <x-dropdown width="w-32">
            <x-slot name="trigger">
                <x-dropdown-trigger-button>
                    {{ __('Actions') }}
                </x-dropdown-trigger-button>
            </x-slot>

            <x-slot name="content">
                <div class="text-left">
                    <x-dropdown-link href="{{ route('projects.edit', [$project]) }}">
                        {{ __('Edit') }}
                    </x-dropdown-link>

                    <x-dropdown-link-button
                        @click.prevent="open = false"
                        wire:click="$toggle('confirmingProjectDeletion')"
                        wire:loading.attr="disabled"
                    >
                        {{ __('Delete') }}
                    </x-dropdown-link-button>
                </div>
            </x-slot>
        </x-dropdown>

        <x-confirmation-modal :id="$project->uuid" wire:model="confirmingProjectDeletion">
            <x-slot name="title">{{ __('Delete project') }}</x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this project? This cannot be undone.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingProjectDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-2" wire:click="deleteProject" wire:loading.attr="disabled">
                    {{ __('Delete project') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </x-table.body-cell>
</tr>
