@props(['action', 'description', 'title'])

<div>
    <header class="max-w-xl mx-auto text-center">
        <h1 class="font-bold text-3xl">{{ $title }}</h1>
        @if (isset($description))
            <div class="mt-4 text-lg">{{ $description }}</div>
        @endif
    </header>

    <div class="max-w-3xl mt-8 mx-auto text-center">{{ $action }}</div>
</div>
