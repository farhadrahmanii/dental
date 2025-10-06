@extends('layouts.app')

@section('title', 'Patient Database - DentalCare Pro')
@section('description', 'Comprehensive patient database management system with advanced search and filtering capabilities.')

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">Patient Database</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                Comprehensive patient management system with advanced search and filtering capabilities
            </p>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="section-apple-sm">
    <div class="container-apple">
        <div class="card-apple-elevated" style="margin-bottom: var(--space-xl);">
            <div style="padding: var(--space-xl);">
                <form method="GET" action="{{ route('patients') }}">
                    <div class="grid-apple" style="grid-template-columns: 2fr 1fr 1fr auto; gap: var(--space-lg); align-items: end;">
                        <div class="form-group-apple">
                            <label class="form-label-apple">Search Patients</label>
                            <div class="search-apple">
                                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                                <input type="text" name="search" class="search-input-apple" placeholder="Search by name, ID, or doctor..." value="{{ request('search') }}">
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Doctor</label>
                            <select name="doctor" class="form-select-apple">
                                <option value="">All Doctors</option>
                                <option value="Dr. Smith" {{ request('doctor') == 'Dr. Smith' ? 'selected' : '' }}>Dr. Smith</option>
                                <option value="Dr. Johnson" {{ request('doctor') == 'Dr. Johnson' ? 'selected' : '' }}>Dr. Johnson</option>
                                <option value="Dr. Williams" {{ request('doctor') == 'Dr. Williams' ? 'selected' : '' }}>Dr. Williams</option>
                            </select>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Treatment</label>
                            <select name="treatment" class="form-select-apple">
                                <option value="">All Treatments</option>
                                <option value="General" {{ request('treatment') == 'General' ? 'selected' : '' }}>General</option>
                                <option value="Cosmetic" {{ request('treatment') == 'Cosmetic' ? 'selected' : '' }}>Cosmetic</option>
                                <option value="Implant" {{ request('treatment') == 'Implant' ? 'selected' : '' }}>Implant</option>
                            </select>
                        </div>
                        
                        <div class="form-group-apple">
                            <button type="submit" class="btn-apple" style="padding: var(--space-md) var(--space-lg);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid-apple grid-apple-4" style="margin-bottom: var(--space-xl);">
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->total() }}</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->where('doctor_name', '!=', null)->count() }}</div>
                <div class="stat-label">With Doctor</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->where('treatment', '!=', null)->count() }}</div>
                <div class="stat-label">In Treatment</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">{{ $patients->where('x_ray_id', '!=', null)->count() }}</div>
                <div class="stat-label">With X-Rays</div>
            </div>
        </div>
    </div>
</section>

<!-- Patient Grid -->
<section class="section-apple" style="background: var(--background);">
    <div class="container-apple">
        @forelse($patients as $patient)
        <div class="patient-card-apple" style="margin-bottom: var(--space-lg);">
            <div class="patient-header">
                <div class="patient-avatar">
                    {{ strtoupper(substr($patient->name, 0, 2)) }}
                </div>
                <div class="patient-info">
                    <h5>{{ $patient->name }}</h5>
                    <div class="patient-id">ID: {{ $patient->register_id }}</div>
                </div>
                <div style="margin-left: auto;">
                    <span class="badge-apple badge-primary">{{ ucfirst($patient->sex) }}</span>
                </div>
            </div>
            
            <div class="patient-details">
                <div class="patient-detail">
                    <div class="patient-detail-label">Age</div>
                    <div class="patient-detail-value">{{ $patient->age }} years</div>
                </div>
                <div class="patient-detail">
                    <div class="patient-detail-label">Father</div>
                    <div class="patient-detail-value">{{ $patient->father_name ?? 'N/A' }}</div>
                </div>
                <div class="patient-detail">
                    <div class="patient-detail-label">Doctor</div>
                    <div class="patient-detail-value">{{ $patient->doctor_name ?? 'Not Assigned' }}</div>
                </div>
                <div class="patient-detail">
                    <div class="patient-detail-label">Treatment</div>
                    <div class="patient-detail-value">{{ $patient->treatment ?? 'Pending' }}</div>
                </div>
                <div class="patient-detail">
                    <div class="patient-detail-label">X-Ray ID</div>
                    <div class="patient-detail-value">{{ $patient->x_ray_id ?? 'N/A' }}</div>
                </div>
                <div class="patient-detail">
                    <div class="patient-detail-label">Images</div>
                    <div class="patient-detail-value">{{ is_array($patient->images) ? count($patient->images) : 0 }}</div>
                </div>
            </div>

            @if($patient->diagnosis)
            <div style="margin-bottom: var(--space-lg);">
                <div class="form-label-apple" style="margin-bottom: var(--space-sm);">Diagnosis</div>
                <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md); max-height: 100px; overflow-y: auto;">
                    <div style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">
                        {!! Str::limit($patient->diagnosis, 200) !!}
                    </div>
                </div>
            </div>
            @endif

            <div style="display: flex; gap: var(--space-sm); justify-content: flex-end;">
                <a href="{{ route('patient.detail', $patient->register_id) }}" class="btn-apple">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    View Details
                </a>
                <a href="/admin" class="btn-apple-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
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
            <h3 class="headline-medium mb-3">No patients found</h3>
            <p class="body-large mb-6" style="color: var(--text-secondary);">
                Try adjusting your search criteria or add new patients through the admin panel.
            </p>
            <a href="/admin" class="btn-apple">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Go to Admin Panel
            </a>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($patients->hasPages())
        <div class="text-center mt-6">
            <div style="display: inline-flex; align-items: center; gap: var(--space-sm); background: var(--surface); padding: var(--space-sm); border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
                {{ $patients->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Database Features Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Advanced Database Features</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Powerful tools for comprehensive patient management
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
                <h3 class="title-large mb-3">Advanced Search</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Search patients by name, doctor, treatment type, or any field in the database.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Smart Filtering</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Filter patients by multiple criteria to quickly find the information you need.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Analytics</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Comprehensive analytics and reporting for better patient care management.
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
                <h3 class="title-large mb-3">Secure Storage</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    HIPAA-compliant secure storage of all patient data and medical records.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection