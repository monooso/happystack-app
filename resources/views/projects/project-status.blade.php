<tr wire:poll.180s="refresh">
    @if ($project->status === \App\Constants\Status::DOWN)
        <x-table.body-heading class="bg-red-50">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-red-700 w-3">@svg('icon-status-down')</div>
                <div class="font-medium text-red-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::WARN)
        <x-table.body-heading class="bg-yellow-50">
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-yellow-500 w-3">@svg('icon-status-warn')</div>
                <div class="font-medium text-yellow-700">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    @if ($project->status === \App\Constants\Status::OKAY)
        <x-table.body-heading>
            <div class="flex flex-nowrap gap-x-2 items-center">
                <div class="text-gray-300 w-3">@svg('icon-status-okay')</div>
                <div class="font-medium">{{ $project->name }}</div>
            </div>
        </x-table.body-heading>
    @endif

    <x-table.body-cell>{{ trans_choice('app.component_count', $project->components->count()) }}</x-table.body-cell>
    <x-table.body-cell>{{ trans_choice('app.component_warnings', $project->components()->warn()->count()) }}</x-table.body-cell>
    <x-table.body-cell>{{ trans_choice('app.component_errors', $project->components()->down()->count()) }}</x-table.body-cell>
</tr>
