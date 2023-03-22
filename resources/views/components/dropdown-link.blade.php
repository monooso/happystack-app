<a {{ $attributes->merge([
    'class' => '
        block
        duration-150
        ease-in-out
        px-4
        py-2
        text-gray-700
        text-sm
        transition
        focus:outline-none
        focus:bg-gray-50
        hover:bg-gray-50
        hover:text-gray-900
']) }}>
    {{ $slot }}
</a>
