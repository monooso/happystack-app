<button {{ $attributes->merge([
    'type' => 'button',
    'class' => '
        bg-white
        border
        border-gray-300
        duration-150
        ease-in-out
        inline-flex
        items-center
        px-6
        py-3
        rounded-md
        text-gray-700
        transition
        active:border-gray-500
        disabled:opacity-25
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-gray-300
        hover:border-gray-400
    ']) }}
>{{ $slot }}</button>
