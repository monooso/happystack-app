<x-base-layout>
    <x-jet-banner/>

    <header>
        <div class="mb-16">
            <nav class="bg-yellow-400 shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between py-4">
                        <div class="flex-shrink-0 h-8 w-8">
                            <x-jet-application-logo />
                        </div>

                        <div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-jet-nav-link
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();"
                                >{{ __('Logout') }}</x-jet-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="max-w-2xl mx-auto text-center text-gray-900">
        <header class="px-4 sm:px-6">
            <h1 class="font-semibold leading-7 text-3xl">{{ $header }}</h1>

            @if (isset($preamble))
                <div class="mt-8 text-lg">{{ $preamble }}</div>
            @endif
        </header>

        <div class="mt-12">
            {{ $slot }}
        </div>
    </main>

    @stack('modals')
</x-base-layout>
