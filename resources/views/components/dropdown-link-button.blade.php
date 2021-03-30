<button {{ $attributes->merge([
    'class' => '
        block
        cursor-pointer
        duration-150
        ease-in-out
        px-4
        py-2
        text-gray-700
        text-sm
        text-left
        transition
        w-full
        focus:outline-none
        focus:bg-gray-50
        hover:bg-gray-50
        hover:text-gray-900
']) }}>
    {{ $slot }}
</button>
