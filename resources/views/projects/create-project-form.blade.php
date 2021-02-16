<x-jet-form-section submit="create">
    <x-slot name="title">{{ __('Project Details') }}</x-slot>
    <x-slot name="description">{{ __('Create a new project to monitor services.') }}</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Project Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label value="{{ __('What would you like to monitor?') }}" />
            <ul>
                @foreach($services as $service)
                <li>
                    <strong>{{ $service->name }}</strong>
                    <ul>
                        @foreach($service->components as $serviceComponent)
                        <x-jet-input id="component.{{ $serviceComponent->id }}" type="checkbox" class="mt-1" value="{{ $serviceComponent->id }}" wire:model.defer="components" />
                        <x-jet-label class="inline-block" for="component.{{ $serviceComponent->id }}" value="{{ $serviceComponent->name }}" />
                        @endforeach
                    </ul>
                </li>
                @endforeach
                <x-jet-input-error for="components" class="mt-2" />
            </ul>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>{{ __('Create') }}</x-jet-button>
    </x-slot>
</x-jet-form-section>
