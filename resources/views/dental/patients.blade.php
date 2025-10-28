@extends('layouts.app')

@section('title', 'Patient Database - DentalCare Pro')
@section('description', 'Comprehensive patient database management system with advanced search and filtering capabilities.')

@section('content')
<!-- Enhanced Apple-style Page Header -->
<section class="section-apple-sm" style="background: linear-gradient(135deg, var(--surface) 0%, var(--apple-gray-1) 100%); position: relative; overflow: hidden;">
    <!-- Background Pattern -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 20% 20%, rgba(0, 122, 255, 0.03) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(0, 122, 255, 0.02) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container-apple" style="position: relative; z-index: 1;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-lg);">
            <div style="flex: 1; min-width: 300px;">
                <h1 class="display-medium mb-2" style="background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    {{ __('dental.patient_database') }}
                </h1>
                <p class="body-large" style="color: var(--text-secondary); max-width: 500px;">
                    {{ __('dental.comprehensive_patient_management') }}
                </p>

                <!-- Quick Stats -->
                <div style="display: flex; gap: var(--space-lg); margin-top: var(--space-lg); flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: var(--radius-full);"></div>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 500;">
                            {{ $patients->total() }} {{ __('dental.total_patients') }}
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: var(--space-sm);">
                        <div style="width: 8px; height: 8px; background: #34C759; border-radius: var(--radius-full);"></div>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 500;">
                            {{ $patients->where('doctor_name', '!=', null)->count() }} {{ __('dental.with_doctor') }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
                <a href="/admin" class="btn-apple-outline" style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md) var(--space-lg);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    {{ __('dental.manage_patients') }}
                </a>
                <a href="{{ route('home') }}#appointment" class="btn-apple" style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md) var(--space-lg);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                    {{ __('dental.book_appointment') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Search and Filter Section -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="card-apple-elevated" style="margin-bottom: var(--space-xl); border: 1px solid var(--border-light);">
            <div style="padding: var(--space-xl);">
                <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-large" style="margin-bottom: var(--space-xs);">{{ __('dental.search_and_filter') }}</h3>
                        <p style="color: var(--text-secondary); font-size: 0.875rem;">{{ __('dental.find_patients_quickly') }}</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('patients') }}" id="searchForm">
                    <div class="grid-apple" style="grid-template-columns: 2fr 1fr auto; gap: var(--space-lg); align-items: end;">
                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.search_patients') }}</label>
                            <div class="search-apple" style="position: relative;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon" style="position: absolute; left: var(--space-md); top: 50%; transform: translateY(-50%); color: var(--text-tertiary); z-index: 1;">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                                <input type="text" name="search" class="search-input-apple" placeholder="{{ __('dental.search_placeholder') }}" value="{{ request('search') }}" style="padding-left: 48px;">
                                @if(request('search'))
                                <button type="button" onclick="clearSearch()" style="position: absolute; right: var(--space-md); top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-tertiary); cursor: pointer; padding: var(--space-xs);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.doctor') }}</label>
                            <select name="doctor" class="form-select-apple">
                                <option value="">{{ __('dental.all_doctors') }}</option>
                                <option value="Dr. Smith" {{ request('doctor') == 'Dr. Smith' ? 'selected' : '' }}>Dr. Smith</option>
                                <option value="Dr. Johnson" {{ request('doctor') == 'Dr. Johnson' ? 'selected' : '' }}>Dr. Johnson</option>
                                <option value="Dr. Williams" {{ request('doctor') == 'Dr. Williams' ? 'selected' : '' }}>Dr. Williams</option>
                            </select>
                        </div>

                        <div class="form-group-apple">
                            <button type="submit" class="btn-apple" style="padding: var(--space-md) var(--space-lg); min-width: 120px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                                {{ __('common.search') }}
                            </button>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request('search') || request('doctor'))
                    <div style="margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--border-light);">
                        <div style="display: flex; align-items: center; gap: var(--space-sm); flex-wrap: wrap;">
                            <span style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 500;">{{ __('dental.active_filters') }}:</span>
                            @if(request('search'))
                            <span class="filter-tag">
                                {{ __('dental.search') }}: "{{ request('search') }}"
                                <button type="button" onclick="removeFilter('search')" style="margin-left: var(--space-xs); background: none; border: none; color: inherit; cursor: pointer;">×</button>
                            </span>
                            @endif
                            @if(request('doctor'))
                            <span class="filter-tag">
                                {{ __('dental.doctor') }}: {{ request('doctor') }}
                                <button type="button" onclick="removeFilter('doctor')" style="margin-left: var(--space-xs); background: none; border: none; color: inherit; cursor: pointer;">×</button>
                            </span>
                            @endif
                            <a href="{{ route('patients') }}" class="btn-apple-outline" style="padding: var(--space-xs) var(--space-sm); font-size: 0.75rem;">
                                {{ __('dental.clear_all') }}
                            </a>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid-apple grid-apple-4" style="margin-bottom: var(--space-xl);">
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->total() }}</div>
                <div class="stat-label">{{ __('dental.total_patients') }}</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->where('doctor_name', '!=', null)->count() }}</div>
                <div class="stat-label">{{ __('dental.with_doctor') }}</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->where('x_ray_id', '!=', null)->count() }}</div>
                <div class="stat-label">{{ __('dental.with_xrays') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Patient Grid -->
<section class="section-apple" style="background: var(--background);">
    <div class="container-apple">
        <!-- Results Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-xl);">
            <div>
                <h2 class="headline-medium" style="margin-bottom: var(--space-sm);">{{ __('dental.patient_records') }}</h2>
                <p style="color: var(--text-secondary);">{{ $patients->total() }} {{ __('dental.patients_found') }}</p>
            </div>
            <div style="display: flex; gap: var(--space-sm);">
                <button onclick="toggleView('grid')" id="gridViewBtn" class="view-toggle-btn active" style="padding: var(--space-sm); border: 1px solid var(--border); background: var(--primary); color: var(--apple-white); border-radius: var(--radius-md); cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </button>
                <button onclick="toggleView('list')" id="listViewBtn" class="view-toggle-btn" style="padding: var(--space-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text-secondary); border-radius: var(--radius-md); cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="patientsGrid" class="patients-grid">
            @forelse($patients as $patient)
            <div class="patient-card-apple enhanced-patient-card" style="margin-bottom: var(--space-lg); position: relative; overflow: hidden;">
                <!-- Status Indicator -->
                <div style="position: absolute; top: var(--space-md); right: var(--space-md); z-index: 2;">
                    @if($patient->doctor_name)
                        <div style="width: 12px; height: 12px; background: #34C759; border-radius: var(--radius-full); border: 2px solid var(--surface);"></div>
                    @else
                        <div style="width: 12px; height: 12px; background: #FF9500; border-radius: var(--radius-full); border: 2px solid var(--surface);"></div>
                    @endif
                </div>

                <div class="patient-header">
                    <div class="patient-avatar" style="position: relative;">
                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                        @if($patient->sex == 'female')
                            <div style="position: absolute; bottom: -2px; right: -2px; width: 16px; height: 16px; background: #FF69B4; border-radius: var(--radius-full); border: 2px solid var(--surface); display: flex; align-items: center; justify-content: center;">
                                <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.89 1 3 1.89 3 3V21C3 22.1 3.89 23 5 23H19C20.1 23 21 22.1 21 21V9M19 9H14V4H5V21H19V9Z"/>
                                </svg>
                            </div>
                        @elseif($patient->sex == 'male')
                            <div style="position: absolute; bottom: -2px; right: -2px; width: 16px; height: 16px; background: #007AFF; border-radius: var(--radius-full); border: 2px solid var(--surface); display: flex; align-items: center; justify-content: center;">
                                <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 9C10.29 9 11.5 9.41 12.47 10.11L17.47 15.11C18.27 15.91 18.27 17.19 17.47 17.99C16.67 18.79 15.39 18.79 14.59 17.99L9.59 12.99C8.89 12.02 8.48 10.81 8.48 9.52C8.48 7.01 10.49 5 13 5C15.51 5 17.52 7.01 17.52 9.52C17.52 12.03 15.51 14.04 13 14.04C11.71 14.04 10.5 13.63 9.53 12.93L4.53 7.93C3.73 7.13 3.73 5.85 4.53 5.05C5.33 4.25 6.61 4.25 7.41 5.05L12.41 10.05C13.11 11.02 13.52 12.23 13.52 13.52C13.52 16.03 11.51 18.04 9 18.04C6.49 18.04 4.48 16.03 4.48 13.52C4.48 11.01 6.49 9 9 9Z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="patient-info">
                        <h5 style="margin-bottom: var(--space-xs);">{{ $patient->name }}</h5>
                        <div class="patient-id">{{ __('dental.id') }}: {{ $patient->register_id }}</div>
                        <div style="color: var(--text-tertiary); font-size: 0.75rem; margin-top: var(--space-xs);">
                            {{ __('dental.added') }} {{ $patient->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <div class="patient-details">
                    <div class="patient-detail">
                        <div class="patient-detail-label">{{ __('dental.age') }}</div>
                        <div class="patient-detail-value">{{ $patient->age }} {{ __('dental.years') }}</div>
                    </div>
                    <div class="patient-detail">
                        <div class="patient-detail-label">{{ __('dental.father') }}</div>
                        <div class="patient-detail-value">{{ $patient->father_name ?? 'N/A' }}</div>
                    </div>
                    <div class="patient-detail">
                        <div class="patient-detail-label">{{ __('dental.doctor') }}</div>
                        <div class="patient-detail-value">{{ $patient->doctor_name ?? __('dental.not_assigned') }}</div>
                    </div>
                    <div class="patient-detail">
                        <div class="patient-detail-label">{{ __('dental.xray_id') }}</div>
                        <div class="patient-detail-value">{{ $patient->x_ray_id ?? 'N/A' }}</div>
                    </div>
                    <div class="patient-detail">
                        <div class="patient-detail-label">{{ __('dental.images') }}</div>
                        <div class="patient-detail-value">{{ is_array($patient->images) ? count($patient->images) : 0 }}</div>
                    </div>
                </div>

                @if($patient->diagnosis)
                <div style="margin-bottom: var(--space-lg);">
                    <div class="form-label-apple" style="margin-bottom: var(--space-sm);">{{ __('dental.diagnosis') }}</div>
                    <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md); max-height: 100px; overflow-y: auto;">
                        <div style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">
                            {!! Str::limit($patient->diagnosis, 200) !!}
                        </div>
                    </div>
                </div>
                @endif

                <div style="display: flex; gap: var(--space-sm); justify-content: flex-end;">
                    <a href="{{ route('patient.detail', $patient->register_id) }}" class="btn-apple" style="flex: 1; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        {{ __('common.view_details') }}
                    </a>
                    <a href="/admin" class="btn-apple-outline" style="flex: 1; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        {{ __('common.edit') }}
                    </a>
                </div>
            </div>
            @empty
        <div class="card-apple-elevated" style="text-align: center; padding: var(--space-4xl);">
            <div style="width: 80px; height: 80px; background: var(--apple-gray-2); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--text-tertiary);">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
            </div>
            <h3 class="headline-medium mb-3">{{ __('dental.no_patients_found') }}</h3>
            <p class="body-large mb-6" style="color: var(--text-secondary);">
                {{ __('dental.try_adjusting_search') }}
            </p>
            <a href="/admin" class="btn-apple">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                {{ __('dental.go_to_admin') }}
            </a>
        </div>
        @endforelse

        <!-- Enhanced Pagination -->
        @if($patients->hasPages())
        <div style="margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 1px solid var(--border-light);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-lg);">
                <div style="color: var(--text-secondary); font-size: 0.875rem;">
                    {{ __('dental.showing') }} {{ $patients->firstItem() }} {{ __('dental.to') }} {{ $patients->lastItem() }} {{ __('dental.of') }} {{ $patients->total() }} {{ __('dental.results') }}
                </div>
                <div class="pagination-apple">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Database Features Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.advanced_features') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.powerful_tools') }}
            </p>
        </div>

        <div class="grid-apple grid-apple-4">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.advanced_search') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.search_description') }}
                </p>
            </div>

            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.smart_filtering') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.filter_description') }}
                </p>
            </div>

            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.analytics') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.analytics_description') }}
                </p>
            </div>

            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <circle cx="12" cy="16" r="1"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.secure_storage') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.secure_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.quick_actions') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.manage_patient_operations') }}
            </p>
        </div>

        <div class="grid-apple grid-apple-3">
            <a href="/admin" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.add_patient') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.register_new_patient') }}
                </p>
            </a>

            <a href="{{ route('home') }}#appointment" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #34C759 0%, #30A46C 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.book_appointment') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.schedule_appointment') }}
                </p>
            </a>

            <a href="{{ route('financial.dashboard') }}" class="card-apple" style="text-align: center; padding: var(--space-xl); text-decoration: none; color: inherit;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #FF9500 0%, #FF8C00 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.financial_reports') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.view_financial_data') }}
                </p>
            </a>
        </div>
    </div>
</section>
@endsection
