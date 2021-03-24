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

        <div class="relative inline-block text-left" x-data="{ open: false }">
            <div>
                <button
                    aria-expanded="true"
                    aria-haspopup="true"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                    id="options-menu"
                    type="button"
                    @click="open = !open"
                >
                    {{ __('Actions') }}
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <div
                aria-labelledby="options-menu"
                aria-orientation="vertical"
                class="absolute origin-top-right right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-10 focus:outline-none"
                role="menu"
                x-show.transition="open"
                @click.away="open = false"
            >
                <div class="py-1" role="none">
                    <a
                        class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        href="{{ route('projects.edit', ['project' => $project]) }}"
                        role="menuitem"
                    >
                        <div class="text-gray-400 w-4 group-hover:text-gray-500">
                            @svg('icon-edit')
                        </div>
                        <div class="ml-4">{{ __('Edit') }}</div>
                    </a>
                </div>

                <div class="py-1" role="none">
                    <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                        <div class="text-gray-400 w-4 group-hover:text-gray-500">
                            @svg('icon-delete')
                        </div>
                        <div class="ml-4">{{ __('Delete') }}</div>
                    </a>
                </div>
            </div>
        </div>
    </x-table.body-cell>
</tr>
