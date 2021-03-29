<header>
    <div class="mb-8">
        <x-checkout.navigation-menu />
    </div>

    <div class="pb-8 px-4 sm:px-8 lg:pb-0 lg:max-w-4xl lg:mx-auto">
        <div>
            <h1 class="font-semibold leading-7 text-3xl text-gray-900">Billing for {{ Auth::user()->currentTeam->name }}</h1>
        </div>
    </div>
</header>
