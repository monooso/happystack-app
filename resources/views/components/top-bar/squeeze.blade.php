<div class="bg-yellow-400 shadow">
    <x-container>
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center py-4">
                    <div class="h-4 w-4">
                        <x-brand-logo-solid/>
                    </div>
                    <div class="font-bold hidden ml-2 sm:block">Happy Stack</div>
                </div>
            </div>

            <nav>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center text-yellow-700 transition-colors hover:text-yellow-900" type="submit">
                        <div>{{ __('Log out') }}</div>
                        <div class="h-4 ml-2 w-4">
                            <x-zondicon-arrow-thin-right />
                        </div>
                    </button>
                </form>
            </nav>
        </div>
    </x-container>
</div>
