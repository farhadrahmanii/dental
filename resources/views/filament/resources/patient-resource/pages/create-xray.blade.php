<x-filament-panels::page>
    @if (session('status'))
        <div class="fi-alert fi-color-success mb-4">
            <div class="fi-alert-content">{{ session('status') }}</div>
        </div>
    @endif

    <div class="max-w-4xl">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-6">
                <div class="text-sm text-gray-500 dark:text-gray-400">Register ID: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $this->record->register_id }}</span></div>
            </div>

            <form wire:submit.prevent="save" class="space-y-8">
                {{ $this->form }}

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Save X-ray
                    </button>
                    <a href="{{ url('/admin/patients') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800 dark:focus:ring-offset-gray-900">
                        Back to Patients
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>


