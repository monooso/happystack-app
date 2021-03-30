<legend {{ $attributes->merge(['class' => 'block text-gray-900']) }}>
    <span class="block font-semibold">{{ $title }}</span>
    @if ($subtitle)
        <span class="block mt-1 text-gray-700 text-sm">{{ $subtitle }}</span>
    @endif
</legend>
