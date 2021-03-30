<nav class="bg-yellow-400 shadow" x-data="{ open: false }">
    <x-container>
        <div class="flex items-center justify-between">
            <!-- Left-hand side -->
            <div class="flex items-center">
                <!-- Branding -->
                <a class="flex items-center py-4" href="{{ route('projects.index') }}">
                    <div class="h-4 w-4">
                        <x-brand-logo-solid/>
                    </div>
                    <div class="font-bold ml-2">Happy Stack</div>
                </a>
            </div>

            <!-- Right-hand side -->
            <div class="hidden sm:flex sm:items-center">
                @if (user()->allTeams()->count() > 1)
                    <div class="ml-4 relative">
                        <x-jet-dropdown align="right">
                            <x-slot name="trigger">
                                <button class="bg-transparent border border-transparent px-4 py-2 rounded transition-colors focus:outline-none focus:border-yellow-100 hover:bg-yellow-300">
                                    <div class="flex items-center text-yellow-900">
                                        <div>{{ user()->currentTeam->name }}</div>
                                        <div class="h4 ml-2 text-yellow-700 w-4">
                                            <x-zondicon-cheveron-down/>
                                        </div>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <div class="font-semibold px-4 py-2 text-sm">{{ __('Switch team') }}</div>
                                    @foreach (user()->allTeams() as $team)
                                        @unless ($team->id === user()->currentTeam->id)
                                            <x-jet-switchable-team :team="$team"/>
                                        @endunless
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endif

                <!-- User settings -->
                <div class="ml-4">
                    <x-jet-dropdown>
                        <x-slot name="trigger">
                            <button class="flex items-center">
                                <div class="bg-yellow-300 border border-yellow-200 duration-150 ease-in-out flex rounded-full transition focus:outline-none focus:border-yellow-100">
                                    <div class="h-10 overflow-hidden rounded-full w-10">
                                        @if (user()->has_profile_photo)
                                            <img class="h-full object-cover w-full" src="{{ user()->profile_photo_url }}" alt="{{ user()->name }}"/>
                                        @else
                                            <div class="p-3 text-yellow-700">
                                                <x-zondicon-user/>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="h4 ml-2 text-yellow-700 w-4">
                                    <x-zondicon-cheveron-down/>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-yellow-700 hover:text-yellow-900 hover:bg-yellow-300 focus:outline-none focus:bg-gray-100 focus:text-yellow-900 transition duration-150 ease-in-out">
                    <div class="h-4 w-4">
                        <x-zondicon-menu/>
                    </div>
                </button>
            </div>
        </div>
    </x-container>

    <!-- Responsive menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="bg-white border-b border-gray-200 py-2">
            <div class="space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-jet-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <x-mobile-nav-divider />

                <div class="font-semibold px-4 py-2 text-sm">{{ __('Switch team') }}</div>
                @foreach (user()->allTeams() as $team)
                    <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link"/>
                @endforeach
            </div>
        </div>
    </div>
</nav>