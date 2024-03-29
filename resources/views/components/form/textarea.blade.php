@props(['disabled' => false])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => '
        block
        border-gray-300
        h-64
        resize-none
        rounded-md
        shadow-sm
        w-full
        focus:border-indigo-300
        focus:ring
        focus:ring-indigo-200
        focus:ring-opacity-50
    ']) !!}
></textarea>
