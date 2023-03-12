<form wire:submit.prevent="save">
    <x-action-section>
        <x-slot name="title">{{ __('Project details') }}</x-slot>
        <x-slot name="description">{{ __('Tell us a bit about your project.') }}</x-slot>

        <x-slot name="content">
            <x-card>
                <x-sleeve>
                    <x-label class="block" for="name" value="{{ __('Project name') }}"/>
                    <x-input class="block mt-2 w-full" id="name" type="text" wire:model.defer="name"/>
                    <x-input-error for="name" class="mt-2"/>
                </x-sleeve>
            </x-card>
        </x-slot>
    </x-action-section>

    <x-section-border/>

    <x-action-section>
        <x-slot name="title">{{ __('Services') }}</x-slot>
        <x-slot name="description">{{ __('Select the services you’d like to monitor.') }}</x-slot>

        <x-slot name="content">
            <x-card class="overflow-hidden">
                <div class="divide-gray-100 divide-y">
                    @foreach($services as $service)
                        @can('view', $service)
                            <div x-data="{ expanded: false }">
                                <button @click.prevent="expanded = ! expanded" class="w-full">
                                    <x-sleeve>
                                        <div class="text-left sm:flex sm:items-center sm:justify-between">
                                            <div class="flex items-center">
                                                <div class="w-6">
                                                    @svg('logo-' . $service->handle)
                                                </div>

                                                <div class="block font-semibold ml-4 text-gray-900">{{ $service->name }}</div>
                                            </div>

                                            <div class="block mt-2 text-sm text-gray-700 sm:mt-0">
                                                {{ trans_choice('app.selected_components', count($this->selectedServiceComponents($service))) }}
                                                (<span class="text-indigo-700 underline" x-text="expanded ? 'hide' : 'show'"></span>)
                                            </div>
                                        </div>
                                    </x-sleeve>
                                </button>

                                <div x-show="expanded === true" class="bg-gray-50 border-b border-gray-200 p-4 shadow-inner sm:p-6">
                                    <div class="grid gap-3 md:grid-cols-2">
                                        @foreach ($service->components as $target)
                                            <label class="col-span-1 flex items-start p-1 select-none">
                                                <x-input class="mt-1" type="checkbox" wire:model="components" value="{{ $target->id }}"/>
                                                <span class="ml-2">{{ $target->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @endforeach
                </div>

                <x-input-error for="components" class="mt-2 p-4 sm:p-6"/>
            </x-card>
        </x-slot>
    </x-action-section>

    <x-section-border/>

    <x-action-section>
        <x-slot name="title">{{ __('Your notifications') }}</x-slot>
        <x-slot name="description">{{ __('Control how we notify you when there’s a problem.') }}</x-slot>

        <x-slot name="content">
            <x-card>
                <x-sleeve>
                    <x-label class="block" for="agency.mail_route" value="{{ __('Who should we email if there’s a problem?') }}"/>
                    <x-input class="block mt-2 w-full" id="agency.mail_route" type="email" placeholder="alert@domain.com" wire:model.defer="agency.mail_route"/>
                    <x-input-error for="agency.mail_route" class="mt-2"/>
                </x-sleeve>
            </x-card>
        </x-slot>
    </x-action-section>

    <x-section-border/>

    <x-action-section>
        <x-slot name="title">{{ __('Client notifications') }}</x-slot>
        <x-slot name="description">{{ __('Control how we notify your client when there’s a problem.') }}</x-slot>

        <x-slot name="content">
            <x-card>
                <x-sleeve>
                    <fieldset class="space-y-4">
                        <x-form.legend>
                            <x-slot name="title">{{ __('Would you like us to notify your client?') }}</x-slot>
                            <x-slot name="subtitle">{{ __('We’ll email your client once per day, at most.') }}</x-slot>
                        </x-form.legend>

                        <div class="flex items-center justify-start space-x-8">
                            <label class="flex items-center select-none">
                                <input name="client.via_mail" type="radio" value="{{ \App\Constants\ToggleValue::ENABLED }}" wire:model="client.via_mail"/>
                                <span class="block ml-1">{{ __('Yes') }}</span>
                            </label>

                            <label class="flex items-center select-none">
                                <input name="client.via_mail" type="radio" value="{{ \App\Constants\ToggleValue::DISABLED }}" wire:model="client.via_mail"/>
                                <span class="block ml-1">{{ __('No') }}</span>
                            </label>
                        </div>
                    </fieldset>

                    @if ($this->notifyClient)
                        <div class="mt-8 space-y-6">
                            <div class="mt-4">
                                <x-label class="block" for="client.mail_route" value="{{ __('What is your client’s email address?') }}"/>
                                <x-input class="block mt-2 w-full" id="client.mail_route" type="email" placeholder="john@bigcorp.com" wire:model.defer="client.mail_route"/>
                                <x-input-error for="client.mail_route" class="mt-2"/>
                            </div>

                            <div>
                                <x-label class="block" for="client.mail_message" value="{{ __('What would you like us to say?') }}"/>
                                <x-form.textarea class="mt-2" id="client.mail_message" wire:model.defer="client.mail_message"></x-form.textarea>
                                <x-input-error for="client.mail_message" class="mt-2"/>
                            </div>
                        </div>
                    @endif
                </x-sleeve>
            </x-card>
        </x-slot>
    </x-action-section>

    <x-section-border/>

    <div class="text-right">
        <x-button>
            @if ($project->exists)
                {{ __('Save changes') }}
            @else
                {{ __('Create project') }}
            @endif
        </x-button>
    </div>
</form>
