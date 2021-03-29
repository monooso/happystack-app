<legend {{ $attributes->merge(['class' => 'block text-gray-700']) }}>
    <span class="block font-semibold">{{ $title }}</span>
    @if ($subtitle)
        <span class="block mt-1 text-gray-600 text-sm">{{ $subtitle }}</span>
    @endif
</legend>
