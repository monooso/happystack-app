<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div>
        <ul class="mt-3 grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-3">
            @foreach($projects as $project)
                <li class="col-span-1 flex overflow-hidden shadow-sm rounded-md">
                    <livewire:projects.project-status :project="$project"></livewire:projects.project-status>
                </li>
            @endforeach

            <div class="flex items-center justify-center">
                <a class="block bg-white border border-green-200 flex items-center p-3 rounded-full shadow-md" href="{{ route('projects.create') }}">
                    <div class="h-6 mr-3 text-green-500 w-auto">
                        <svg height="100%" viewBox="0 0 140 140" width="100%" xmlns="http://www.w3.org/2000/svg">
                            <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
                                <path d="M12,23.5A11.5,11.5,0,1,0,.5,12,11.513,11.513,0,0,0,12,23.5ZM6,11h4.75a.25.25,0,0,0,.25-.25V6a1,1,0,0,1,2,0v4.75a.25.25,0,0,0,.25.25H18a1,1,0,0,1,0,2H13.25a.25.25,0,0,0-.25.25V18a1,1,0,0,1-2,0V13.25a.25.25,0,0,0-.25-.25H6a1,1,0,0,1,0-2Z" fill="currentColor" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
                            </g>
                        </svg>
                    </div>

                    <span class="font-bold pr-2 text-green-800">{{ __('Create project') }}</span>
                </a>
            </div>
        </ul>
    </div>
</x-app-layout>
