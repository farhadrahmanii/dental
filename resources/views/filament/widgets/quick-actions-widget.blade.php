<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            /* --- Card Row Layout --- */
            .card-row {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1.5rem;
                padding: 1rem;
            }

            /* --- Individual Card --- */
            .card-row a {
                flex: 1 1 230px;
                max-width: 260px;
                text-decoration: none;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.2));
                backdrop-filter: blur(12px);
                border-radius: 1.25rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.4);
                padding: 2rem 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            /* --- Hover Animation --- */
            .card-row a:hover {
                transform: translateY(-6px) scale(1.03);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            /* --- Icon Style --- */
            .card-row a .icon {
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                margin-bottom: 1.25rem;
                box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.3);
                transition: all 0.3s ease;
            }

            .card-row a:hover .icon {
                transform: scale(1.1);
                box-shadow: 0 0 12px rgba(255, 255, 255, 0.5);
            }

            /* --- Text --- */
            .card-row a p:first-of-type {
                font-weight: 600;
                font-size: 1.1rem;
                color: #1e293b;
            }

            .card-row a p:last-of-type {
                font-size: 0.9rem;
                color: #475569;
                margin-top: 0.25rem;
            }

            /* --- Dark Mode --- */
            @media (prefers-color-scheme: dark) {
                .card-row a {
                    background: linear-gradient(135deg, rgba(30, 30, 30, 0.8), rgba(50, 50, 50, 0.5));
                    border: 1px solid rgba(255, 255, 255, 0.1);
                }

                .card-row a p:first-of-type {
                    color: #f1f5f9;
                }

                .card-row a p:last-of-type {
                    color: #94a3b8;
                }
            }
        </style>

        <div class="card-row">

            <!-- New Patient -->
            <a href="{{ route('filament.admin.resources.patients.create') }}">
                <div class="icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <p>New Patient</p>
                <p>Register a new patient</p>
            </a>

            <!-- New Appointment -->
            <a href="{{ route('filament.admin.resources.appointments.create') }}">
                <div class="icon" style="background:linear-gradient(135deg,#22c55e,#4ade80);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p>Book Appointment</p>
                <p>Schedule a new appointment</p>
            </a>

            <!-- Add Service -->
            <a href="{{ route('filament.admin.resources.services.create') }}">
                <div class="icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <p>Add Service</p>
                <p>Create a new dental service</p>
            </a>

            <!-- Record Payment -->
            <a href="{{ route('filament.admin.resources.payments.create') }}">
                <div class="icon" style="background:linear-gradient(135deg,#f97316,#fb923c);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p>Record Payment</p>
                <p>Add a new payment record</p>
            </a>

            <!-- Patient Database -->
            <a href="{{ route('patients') }}">
                <div class="icon" style="background:linear-gradient(135deg,#3b82f6,#93c5fd);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p>Patient Database</p>
                <p>View all patients</p>
            </a>

            <!-- Financial Reports -->
            <a href="{{ route('financial.dashboard') }}">
                <div class="icon" style="background:linear-gradient(135deg,#16a34a,#4ade80);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p>Financial Reports</p>
                <p>View analytics and data</p>
            </a>

            <!-- Public Booking -->
            <a href="{{ route('home') }}#appointment">
                <div class="icon" style="background:linear-gradient(135deg,#a855f7,#c084fc);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <p>Public Booking</p>
                <p>Frontend appointment form</p>
            </a>

            <!-- Manage Services -->
            <a href="{{ route('filament.admin.resources.services.index') }}">
                <div class="icon" style="background:linear-gradient(135deg,#6366f1,#818cf8);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p>Manage Services</p>
                <p>View and edit services</p>
            </a>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
