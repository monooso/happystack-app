<form wire:submit.prevent="create">
    <div>
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Project details') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Tell us a bit about your project.') }}
            </x-slot>

            <x-slot name="content">
                <x-section-content>
                    <x-jet-label class="block" for="projectName" value="{{ __('Project name') }}"/>
                    <x-jet-input class="block mt-2 w-full" id="projectName" name="projectName" type="text" wire:model="projectName"/>
                    <x-jet-input-error for="projectName" class="mt-2"/>
                </x-section-content>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Services') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Select the services you’d like to monitor.') }}
            </x-slot>

            <x-slot name="content">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="divide-gray-100 divide-y">
                        @foreach($services as $service)
                            <label class="block flex items-center justify-between px-4 py-5 select-none sm:p-6">
                                <div class="flex items-start justify-start">
                                    <div class="flex-0 mr-2 mt-1 w-4">
                                        {!! $service->logoSvg !!}
                                    </div>

                                    <div class="flex-1">
                                        <span class="block font-medium text-gray-900">{{ $service->name }}</span>
                                        <span class="block text-sm text-gray-500">{{ $service->description }}</span>
                                    </div>
                                </div>

                                <div>
                                    <x-jet-input id="projectServices.{{ $service->id }}" name="projectServices.{{ $service->id }}" type="checkbox" wire:model="projectServices.{{ $service->id }}"/>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <x-jet-input-error for="projectServices" class="mt-2"/>
                </div>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Your notifications') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Control how we notify you when there’s a problem.') }}
            </x-slot>

            <x-slot name="content">
                <x-section-content>
                    <x-jet-label class="block" for="notificationEmail" value="{{ __('Who should we email if there’s a problem?') }}"/>
                    <x-jet-input class="block mt-2 w-full" id="notificationEmail" name="notificationEmail" type="email" placeholder="alert@domain.com" wire:model="notificationEmail"/>
                    <x-jet-input-error for="notificationEmail" class="mt-2"/>
                </x-section-content>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">
                {{ __('Client notifications') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Control whether we notify your client when there’s a problem.') }}
            </x-slot>

            <x-slot name="content">
                <x-section-content>
                    <div class="space-y-4">
                        <fieldset>
                            <legend class="block text-sm font-medium text-gray-700">{{ __('Would you like us to notify your client?') }}</legend>
                            <div class="flex items-center justify-start mt-2 space-x-8">
                                <label class="flex items-center">
                                    <input name="notifyClient" type="radio" value="1" wire:model="notifyClient"/>
                                    <span class="block ml-1">{{ __('Yes') }}</span>
                                </label>

                                <label class="flex items-center">
                                    <input name="notifyClient" type="radio" value="0" wire:model="notifyClient"/>
                                    <span class="block ml-1">{{ __('No') }}</span>
                                </label>
                            </div>
                        </fieldset>

                        @if ($notifyClient)
                            <div>
                                <x-jet-label class="block" for="clientNotificationName" value="{{ __('How should we address your client?') }}"/>
                                <x-jet-input class="block mt-2 w-full" id="clientNotificationName" name="clientNotificationName" type="text" placeholder="John" wire:model="clientNotificationName"/>
                                <x-jet-input-error for="clientNotificationName" class="mt-2"/>
                            </div>

                            <div>
                                <x-jet-label class="block" for="clientNotificationEmail" value="{{ __('What is your client’s email address?') }}"/>
                                <x-jet-input class="block mt-2 w-full" id="clientNotificationEmail" name="clientNotificationEmail" type="email" placeholder="john@bigcorp.com" wire:model="clientNotificationEmail"/>
                                <x-jet-input-error for="clientNotificationEmail" class="mt-2"/>
                            </div>
                        @endif

                        <div>
                            <label class="flex items-center">
                                <input class="sr-only mr-1" type="checkbox" wire:model="showClientNotificationEmailPreview"/>
                                <span class="block">{{ __('Show preview') }}</span>
                            </label>
                        </div>

                        @if ($showClientNotificationEmailPreview)
                            <div>
                                <p>Hi John,</p>
                                <p>One of the services your site relies on is having a few issues. We’re monitoring the situation, and will let you know once it’s resolved.</p>
                                <p>Best regards,<br/>Your name</p>
                            </div>
                        @endif
                    </div>
                </x-section-content>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 text-right sm:mt-0">
        <x-jet-button>{{ __('Create project') }}</x-jet-button>
    </div>
</form>
