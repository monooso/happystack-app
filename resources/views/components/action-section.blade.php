<div class="md:gap-6 md:grid md:grid-cols-3" {{ $attributes }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-4 md:col-span-2 md:mt-0">
        {{ $content }}
    </div>
</div>
