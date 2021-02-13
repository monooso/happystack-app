<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <p>PROJECT ALL THE THINGS!</p>
            <ul>
                @foreach($projects as $project)
                    <li>{{ $project->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
