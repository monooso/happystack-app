<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        bg-indigo-700
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
        active:bg-indigo-900
        disabled:opacity-25
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-indigo-300
        hover:bg-indigo-800
    ']) }}
>{{ $slot }}</button>
