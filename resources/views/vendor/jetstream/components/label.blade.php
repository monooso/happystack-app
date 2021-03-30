@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-gray-900']) }}>
    {{ $value ?? $slot }}
</label>
