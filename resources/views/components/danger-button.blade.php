<button {{ $attributes->merge([
    'type' => 'button',
    'class' => '
        bg-red-700
        border
        border-transparent
        duration-150
        ease-in-out
        font-semibold
        inline-flex
        items-center
        px-6
        py-3
        rounded-md
        text-white
        transition
        active:bg-red-900
        disabled:opacity-25
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-red-300
        hover:bg-red-800
    ']) }}
>{{ $slot }}</button>
