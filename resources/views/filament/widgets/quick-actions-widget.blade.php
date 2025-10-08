<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- New Patient -->
            <a href="{{ route('filament.admin.resources.patients.create') }}" 
               class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 rounded-lg hover:shadow-lg transition-all duration-200 group border-2 border-transparent hover:border-blue-400 dark:hover:border-blue-600">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-blue-500 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">👤</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">New Patient</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Register a new patient</p>
            </a>

            <!-- New Appointment -->
            <a href="{{ route('filament.admin.resources.appointments.create') }}" 
               class="p-6 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 rounded-lg hover:shadow-lg transition-all duration-200 group border-2 border-transparent hover:border-green-400 dark:hover:border-green-600">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-green-500 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">📅</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Book Appointment</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Schedule new appointment</p>
            </a>

            <!-- New Service -->
            <a href="{{ route('filament.admin.resources.services.create') }}" 
               class="p-6 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950 dark:to-purple-900 rounded-lg hover:shadow-lg transition-all duration-200 group border-2 border-transparent hover:border-purple-400 dark:hover:border-purple-600">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-purple-500 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <span class="text-2xl">🦷</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Add Service</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Create dental service</p>
            </a>

            <!-- New Payment -->
            <a href="{{ route('filament.admin.resources.payments.create') }}" 
               class="p-6 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-950 dark:to-orange-900 rounded-lg hover:shadow-lg transition-all duration-200 group border-2 border-transparent hover:border-orange-400 dark:hover:border-orange-600">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-orange-500 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">💰</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Record Payment</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Add new payment record</p>
            </a>
        </div>

        <!-- Secondary Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('patients') }}" 
               class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded mr-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Patient Database</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">View all patients</p>
                </div>
            </a>

            <a href="{{ route('financial.dashboard') }}" 
               class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded mr-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Financial Reports</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">View analytics</p>
                </div>
            </a>

            <a href="{{ route('home') }}#appointment" 
               class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded mr-3">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Public Booking</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Frontend form</p>
                </div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
