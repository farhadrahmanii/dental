<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Patient Information
        </x-slot>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Card 2 -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                <label class="inline-flex items-center gap-x-3">
                    <span class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                        Patient Name:
                    </span>
                    <span class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                         {{ $this->record->name }}
                    </span>
                </label>
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
                <x-filament::button style="margin: 18px" type="submit">
                    Save X-ray
                </x-filament::button>

                <x-filament::button style="margin: 18px"
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
