<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-sm font-medium">Quick Actions</span>
            </div>
        </x-slot>

        <div class="flex flex-wrap gap-2">
            <!-- Create Patient Button -->
            <a href="{{ route('filament.admin.resources.patients.create') }}" 
               class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors duration-200">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                New Patient
            </a>

            <!-- Record Payment Button -->
            <a href="{{ route('filament.admin.resources.payments.create') }}" 
               class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md text-white bg-success-600 hover:bg-success-700 transition-colors duration-200">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Record Payment
            </a>

            <!-- Schedule Appointment Button -->
            <a href="{{ route('home') }}#appointment" 
               target="_blank"
               class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md text-white bg-warning-600 hover:bg-warning-700 transition-colors duration-200">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Book Appointment
            </a>

            <!-- View Reports Button -->
            <a href="{{ route('financial.dashboard') }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md text-white bg-info-600 hover:bg-info-700 transition-colors duration-200">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Reports
            </a>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
