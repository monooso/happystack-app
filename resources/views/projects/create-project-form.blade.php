<form wire:submit.prevent="create">
    <div>
        <x-jet-action-section>
            <x-slot name="title">{{ __('Project details') }}</x-slot>
            <x-slot name="description">{{ __('Tell us a bit about your project.') }}</x-slot>

            <x-slot name="content">
                <x-section-content>
                    <x-jet-label class="block" for="name" value="{{ __('Project name') }}"/>
                    <x-jet-input class="block mt-2 w-full" id="name" type="text" wire:model.defer="name"/>
                    <x-jet-input-error for="name" class="mt-2"/>
                </x-section-content>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">{{ __('Services') }}</x-slot>
            <x-slot name="description">{{ __('Select the services you’d like to monitor.') }}</x-slot>

            <x-slot name="content">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="divide-gray-100 divide-y">
                        @foreach($services as $service)
                            <div x-data="{ expanded: false }">
                                <button @click.prevent="expanded = ! expanded" class="flex items-center justify-between px-4 py-5 w-full sm:p-6">
                                    <div class="flex items-start">
                                        <div class="flex-0 mr-2 mt-1 w-4">
                                            {!! $service->logoSvg !!}
                                        </div>

                                        <div class="flex-1 text-left">
                                            <span class="block font-medium text-gray-900">{{ $service->name }}</span>
                                            <span class="block text-sm text-gray-500">{{ trans_choice('app.selected_components', count($this->selectedServiceComponents($service))) }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-sm text-gray-500" x-text="expanded ? 'Hide components' : 'Show components'" />
                                    </div>
                                </button>

                                <div x-show="expanded === true" class="bg-gray-100 border-b border-gray-200 p-4 shadow-inner sm:p-6">
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach ($service->components as $target)
                                            <label class="col-span-1 flex items-center p-1 select-none">
                                                <x-jet-input type="checkbox" wire:model="components" value="{{ $target->id }}"/>
                                                <span class="ml-2">{{ $target->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <x-jet-input-error for="components" class="mt-2 p-4 sm:p-6"/>
                </div>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">{{ __('Your notifications') }}</x-slot>
            <x-slot name="description">{{ __('Control how we notify you when there’s a problem.') }}</x-slot>

            <x-slot name="content">
                <x-section-content>
                    <x-jet-label class="block" for="channels.email" value="{{ __('Who should we email if there’s a problem?') }}"/>
                    <x-jet-input class="block mt-2 w-full" id="agencyChannels.email.route" type="email" placeholder="alert@domain.com" wire:model.defer="agencyChannels.email.route"/>
                    <x-jet-input-error for="agencyChannels.email.route" class="mt-2"/>
                </x-section-content>
            </x-slot>
        </x-jet-action-section>
    </div>

    <x-jet-section-border/>

    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>
            <x-slot name="title">{{ __('Client notifications') }}</x-slot>
            <x-slot name="description">{{ __('Control how we notify your client when there’s a problem.') }}</x-slot>

            <x-slot name="content">
                <x-section-content>
                    <div>
                        <fieldset class="space-y-4">
                            <legend class="block">
                                <span class="block font-medium text-gray-900">{{ __('Would you like us to notify your client?') }}</span>
                                <span class="block mt-1 text-sm text-gray-500">{{ __('We’ll email your client once per day, at most.') }}</span>
                            </legend>

                            <div class="flex items-center justify-start space-x-8">
                                <label class="flex items-center select-none">
                                    <input name="emailClient" type="radio" value="{{ \App\Constants\ToggleValue::ENABLED }}" wire:model="clientChannels.email.enabled"/>
                                    <span class="block ml-1">{{ __('Yes') }}</span>
                                </label>

                                <label class="flex items-center select-none">
                                    <input name="emailClient" type="radio" value="{{ \App\Constants\ToggleValue::DISABLED }}" wire:model="clientChannels.email.enabled"/>
                                    <span class="block ml-1">{{ __('No') }}</span>
                                </label>
                            </div>
                        </fieldset>

                        @if ($this->clientEmailNotificationsEnabled)
                            <div class="mt-8 space-y-6">
                                <div class="mt-4">
                                    <x-jet-label class="block" for="clientEmailRoute" value="{{ __('What is your client’s email address?') }}"/>
                                    <x-jet-input class="block mt-2 w-full" id="clientEmailRoute" type="email" placeholder="john@bigcorp.com" wire:model.defer="clientChannels.email.route"/>
                                    <x-jet-input-error for="clientChannels.email.route" class="mt-2"/>
                                </div>

                                <div>
                                    <x-jet-label class="block" for="clientEmailMessage" value="{{ __('What would you like us to say?') }}"/>
                                    <x-textarea class="mt-2" id="clientEmailMessage" wire:model.defer="clientChannels.email.message"></x-textarea>
                                    <x-jet-input-error for="clientChannels.email.message" class="mt-2"/>
                                </div>
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
