<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Patient Information
        </x-slot>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="fi-fo-field-wire-label inline-flex items-center gap-x-3">
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        Register ID
                    </span>
                </label>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->record->register_id }}
                </div>
            </div>

            <div>
                <label class="fi-fo-field-wire-label inline-flex items-center gap-x-3">
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        Patient Name
                    </span>
                </label>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->record->name }}
                </div>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            X-ray Details
        </x-slot>

        <form wire:submit.prevent="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button type="submit">
                    Save X-ray
                </x-filament::button>

                <x-filament::button
                    color="gray"
                    :href="App\Filament\Resources\PatientResource::getUrl('view', ['record' => $this->record->register_id])"
                    tag="a"
                >
                    Cancel
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
