<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <form wire:submit.prevent="create">
            <div class="bg-white divide-gray-200 divide-y overflow-hidden shadow sm:rounded-lg">
                <section class="px-4 py-5 sm:p-6">
                    <h2>{{ __('Project details') }}</h2>

                    <div class="mt-4 space-y-2">
                        <x-jet-label class="block" for="projectName" value="{{ __('Project name') }}" />
                        <x-jet-input class="block mt-2 w-full" id="projectName" name="projectName" type="text" wire:model="projectName" />
                        <x-jet-input-error for="projectName" class="mt-2" />
                    </div>
                </section>

                <fieldset class="px-4 py-5 sm:p-6">
                    <legend>{{ __('Services to monitor') }}</legend>

                    <div class="mt-4 space-y-2">
                        @foreach($services as $service)
                        <div class="flex justify-between">
                            <div>
                                <x-jet-input id="projectServices.{{ $service->id }}" name="projectServices.{{ $service->id }}" type="checkbox" wire:model="projectServices.{{ $service->id }}" />
                                <x-jet-label class="inline-block" for="projectServices.{{ $service->id }}" value="{{ $service->name }}" />
                            </div>
                            <div>
                                <span>{{ $service->components->count() }} components</span>
                            </div>
                        </div>
                        @endforeach
                        <x-jet-input-error for="projectServices" class="mt-2" />
                    </div>
                </fieldset>

                <section class="px-4 py-5 sm:p-6">
                    <h2>{{ __('Notifications') }}</h2>

                    <div class="mt-4 space-y-8">
                        <div>
                            <x-jet-label class="block" for="notificationEmail" value="{{ __('Who should we email if there’s a problem?') }}" />
                            <x-jet-input class="block mt-2 w-full" id="notificationEmail" name="notificationEmail" type="email" placeholder="alert@domain.com" wire:model="notificationEmail" />
                            <x-jet-input-error for="notificationEmail" class="mt-2" />
                        </div>

                        <fieldset>
                            <legend class="block text-sm font-medium text-gray-700">{{ __('Would you like us to notify your client?') }}</legend>
                            <div class="flex items-center justify-start mt-2 space-x-8">
                                <label class="flex items-center">
                                    <input name="notifyClient" type="radio" value="1" wire:model="notifyClient" />
                                    <span class="block ml-1">{{ __('Yes') }}</span>
                                </label>

                                <label class="flex items-center">
                                    <input name="notifyClient" type="radio" value="0" wire:model="notifyClient" />
                                    <span class="block ml-1">{{ __('No') }}</span>
                                </label>
                            </div>
                        </fieldset>

                        @if ($notifyClient)
                        <div>
                            <x-jet-label class="block" for="clientNotificationName" value="{{ __('How should we address your client?') }}" />
                            <x-jet-input class="block mt-2 w-full" id="clientNotificationName" name="clientNotificationName" type="text" placeholder="John" wire:model="clientNotificationName" />
                            <x-jet-input-error for="clientNotificationName" class="mt-2" />
                        </div>

                        <div>
                            <x-jet-label class="block" for="clientNotificationEmail" value="{{ __('What is your client’s email address?') }}" />
                            <x-jet-input class="block mt-2 w-full" id="clientNotificationEmail" name="clientNotificationEmail" type="email" placeholder="john@bigcorp.com" wire:model="clientNotificationEmail" />
                            <x-jet-input-error for="clientNotificationEmail" class="mt-2" />
                        </div>
                        @endif

                        <div>
                            <label class="flex items-center">
                                <input class="sr-only mr-1" type="checkbox" wire:model="showClientNotificationEmailPreview" />
                                <span class="block">{{ __('Show preview') }}</span>
                            </label>
                        </div>

                        @if ($showClientNotificationEmailPreview)
                        <div>
                            <p>Hi John,</p>
                            <p>One of the services your site relies on is having a few issues. We’re monitoring the situation, and will let you know once it’s resolved.</p>
                            <p>Best regards,<br />Your name</p>
                        </div>
                        @endif
                    </div>
                </section>

                <section class="px-4 py-5 sm:p-6">
                    <x-jet-button>{{ __('Create project') }}</x-jet-button>
                </section>
            </div>
        </form>
    </div>
</div>
