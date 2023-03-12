@props(['active'])

@php
$classes = '
    block
    duration-150
    ease-in-out
    px-4
    py-2
    transition-colors
    focus:bg-gray-50
    focus:outline-none
    focus:text-gray-900
    hover:bg-gray-50
    hover:text-gray-900
';

$classes .= ($active ?? false)
    ? ' bg-gray-50 font-semibold text-gray-900'
    : ' text-gray-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
