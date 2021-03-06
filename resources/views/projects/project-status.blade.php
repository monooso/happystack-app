<div class="
    bg-white border-t-4 w-full
    @if ($project->hasComponentErrors)
        {{ 'border-red-500' }}
    @elseif ($project->hasComponentWarnings)
        {{ 'border-yellow-500' }}
    @else
        {{ 'border-green-500' }}
    @endif
">
    <div class="p-6">
        <header class="mb-8">
            <h3 class="font-medium text-xl">{{ $project->name }}</h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('app.last_updated', ['time_interval' => $project->updatedAtForHumans]) }}
            </p>
        </header>

        <div class="flex items-center justify-start gap-4">
            @if ($project->hasComponentErrors)
                <div class="bg-red-50 border border-red-100 flex items-center px-2 py-1 rounded-md">
                    <div class="h-auto text-red-500 w-3">
                        <svg fill="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <rect height="100" width="100"></rect>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm text-red-700">
                        {{ trans_choice('app.component_errors', $project->componentsWithErrors->count()) }}
                    </span>
                </div>
            @endif

            @if ($project->hasComponentWarnings)
                <div class="bg-yellow-50 border border-yellow-100 flex items-center px-2 py-1 rounded-md">
                    <div class="h-auto text-yellow-500 w-3">
                        <svg fill="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="50,0 100,100 0,100"></polygon>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm text-yellow-700">
                        {{ trans_choice('app.component_warnings', $project->componentsWithWarnings->count()) }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
