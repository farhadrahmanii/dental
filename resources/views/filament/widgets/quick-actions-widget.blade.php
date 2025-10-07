<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </div>
        </x-slot>
        
        <x-slot name="description">
            Quickly create patients, record payments, and manage appointments
        </x-slot>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Create Patient Button -->
            <a href="{{ route('filament.admin.resources.patients.create') }}" 
               class="inline-flex items-center justify-center h-16 px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                <div class="flex flex-col items-center gap-1 text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <div class="text-xs font-medium">
                        New Patient
                    </div>
                </div>
            </a>

            <!-- Record Payment Button -->
            <a href="{{ route('filament.admin.resources.payments.create') }}" 
               class="inline-flex items-center justify-center h-16 px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-success-600 hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500 transition-colors duration-200">
                <div class="flex flex-col items-center gap-1 text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <div class="text-xs font-medium">
                        Record Payment
                    </div>
                </div>
            </a>

            <!-- Schedule Appointment Button -->
            <a href="{{ route('home') }}#appointment" 
               target="_blank"
               class="inline-flex items-center justify-center h-16 px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-warning-600 hover:bg-warning-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning-500 transition-colors duration-200">
                <div class="flex flex-col items-center gap-1 text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-xs font-medium">
                        Book Appointment
                    </div>
                </div>
            </a>

            <!-- View Reports Button -->
            <a href="{{ route('financial.dashboard') }}" 
               target="_blank"
               class="inline-flex items-center justify-center h-16 px-3 py-2 border border-transparent text-xs font-medium rounded-lg text-white bg-info-600 hover:bg-info-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-info-500 transition-colors duration-200">
                <div class="flex flex-col items-center gap-1 text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div class="text-xs font-medium">
                        View Reports
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Stats Row -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary-500 rounded-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-primary-700 dark:text-primary-300">
                                Total Patients
                            </div>
                            <div class="text-2xl font-bold text-primary-900 dark:text-primary-100">
                                {{ \App\Models\Patient::count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-success-50 dark:bg-success-900/20 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-success-500 rounded-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-success-700 dark:text-success-300">
                                Total Revenue
                            </div>
                            <div class="text-2xl font-bold text-success-900 dark:text-success-100">
                                ${{ number_format(\App\Models\Payment::sum('amount'), 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-warning-50 dark:bg-warning-900/20 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-warning-500 rounded-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-warning-700 dark:text-warning-300">
                                Today's Appointments
                            </div>
                            <div class="text-2xl font-bold text-warning-900 dark:text-warning-100">
                                {{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
