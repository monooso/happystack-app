@props(['active'])

@php
    $classes = '
        bg-transparent
        border
        border-transparent
        duration-150
        ease-in-out
        inline-flex
        items-center
        px-4
        py-2
        rounded
        text-yellow-900
        transition-colors
        focus:bg-yellow-300
        focus:outline-none
        focus:text-yellow-900
        hover:bg-yellow-300
        hover:text-yellow-900
    ';

    if ($active ?? false) {
        $classes .= ' bg-yellow-300 font-semibold';
    }
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
