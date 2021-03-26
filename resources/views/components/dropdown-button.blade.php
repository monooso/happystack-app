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
        px-3
        py-2
        rounded-md
        text-gray-700
        text-sm
        transition
        active:border-gray-500
        disabled:opacity-25
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
        focus:ring-gray-300
        hover:border-gray-400
    ']) }}
>
    {{ $slot }}
    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
    </svg>
</button>
