@extends('layouts.app')

@section('title', 'Patient Details - ' . $patient->name . ' - DentalCare Pro')
@section('description', 'Detailed patient information and treatment history for ' . $patient->name . '.')

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">Patient Details</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                Comprehensive patient information and treatment history
            </p>
        </div>
    </div>
</section>

<!-- Patient Information -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 2fr 1fr; gap: var(--space-2xl);">
            <!-- Main Patient Info -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-2xl);">
                    <div class="patient-header" style="margin-bottom: var(--space-xl);">
                        <div class="patient-avatar" style="width: 80px; height: 80px; font-size: 1.5rem;">
                            {{ strtoupper(substr($patient->name, 0, 2)) }}
                        </div>
                        <div class="patient-info">
                            <h2 class="headline-medium mb-2">{{ $patient->name }}</h2>
                            <div class="patient-id" style="font-size: 1rem;">Patient ID: {{ $patient->register_id }}</div>
                            <div style="margin-top: var(--space-sm);">
                                <span class="badge-apple badge-primary">{{ ucfirst($patient->sex) }}</span>
                                @if($patient->doctor_name)
                                    <span class="badge-apple badge-success" style="margin-left: var(--space-sm);">Under Care</span>
                                @else
                                    <span class="badge-apple badge-warning" style="margin-left: var(--space-sm);">Pending Assignment</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Patient Details Grid -->
                    <div class="grid-apple" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-xl);">
                        <div class="form-group-apple">
                            <label class="form-label-apple">Age</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large" style="font-weight: 600;">{{ $patient->age }} years</span>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Father's Name</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large">{{ $patient->father_name ?? 'Not provided' }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Assigned Doctor</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large">{{ $patient->doctor_name ?? 'Not assigned' }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Treatment Type</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large">{{ $patient->treatment ?? 'Pending' }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">X-Ray ID</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large">{{ $patient->x_ray_id ?? 'Not available' }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Images Count</label>
                            <div style="background: var(--apple-gray-1); padding: var(--space-md); border-radius: var(--radius-md);">
                                <span class="body-large">{{ is_array($patient->images) ? count($patient->images) : 0 }} images</span>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis Section -->
                    @if($patient->diagnosis)
                    <div class="form-group-apple">
                        <label class="form-label-apple">Diagnosis</label>
                        <div style="background: var(--apple-gray-1); padding: var(--space-lg); border-radius: var(--radius-md); max-height: 300px; overflow-y: auto;">
                            <div style="color: var(--text-secondary); line-height: 1.6;">
                                {!! nl2br(e($patient->diagnosis)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Images Section -->
                    @if($patient->images && is_array($patient->images) && count($patient->images) > 0)
                    <div class="form-group-apple">
                        <label class="form-label-apple">Patient Images</label>
                        <div class="grid-apple" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-md);">
                            @foreach($patient->images as $image)
                            <div class="card-apple" style="padding: var(--space-md); text-align: center;">
                                <img src="{{ $image }}" alt="Patient Image" class="img-small img-optimized" style="margin-bottom: var(--space-sm);">
                                <div class="body-medium" style="color: var(--text-tertiary);">Image {{ $loop->iteration }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="card-apple" style="padding: var(--space-xl); margin-bottom: var(--space-lg);">
                    <h3 class="title-large mb-4">Quick Actions</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="/admin" class="btn-apple">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit Patient
                        </a>
                        <a href="{{ route('patients') }}" class="btn-apple-outline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Back to Patients
                        </a>
                        <a href="{{ route('home') }}#appointment" class="btn-apple-outline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12,6 12,12 16,14"/>
                            </svg>
                            Book Appointment
                        </a>
                    </div>
                </div>

                <!-- Patient Stats -->
                <div class="card-apple" style="padding: var(--space-xl); margin-bottom: var(--space-lg);">
                    <h3 class="title-large mb-4">Patient Statistics</h3>
                    <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                        <div class="stat-apple" style="padding: var(--space-md);">
                            <div class="stat-number" style="font-size: 1.5rem;">{{ $patient->age }}</div>
                            <div class="stat-label">Years Old</div>
                        </div>
                        <div class="stat-apple" style="padding: var(--space-md);">
                            <div class="stat-number" style="font-size: 1.5rem;">{{ is_array($patient->images) ? count($patient->images) : 0 }}</div>
                            <div class="stat-label">Images</div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card-apple" style="padding: var(--space-xl);">
                    <h3 class="title-large mb-4">Contact Information</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span class="body-medium">+1 (555) 123-4567</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <span class="body-medium">info@dentalcarepro.com</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: var(--space-sm);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span class="body-medium">123 Dental Street</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Treatment History -->
@if($patient->diagnosis || $patient->treatment)
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Treatment History</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Complete treatment information and medical history
            </p>
        </div>
        
        <div class="card-apple-elevated">
            <div style="padding: var(--space-2xl);">
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-xl);">
                    <div>
                        <h3 class="title-large mb-4">Current Treatment</h3>
                        <div style="background: var(--apple-gray-1); padding: var(--space-lg); border-radius: var(--radius-md);">
                            <div class="body-large" style="font-weight: 600; color: var(--primary); margin-bottom: var(--space-sm);">
                                {{ $patient->treatment ?? 'No active treatment' }}
                            </div>
                            <div class="body-medium" style="color: var(--text-secondary);">
                                @if($patient->doctor_name)
                                    Under the care of {{ $patient->doctor_name }}
                                @else
                                    Treatment pending doctor assignment
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="title-large mb-4">Medical Records</h3>
                        <div style="background: var(--apple-gray-1); padding: var(--space-lg); border-radius: var(--radius-md);">
                            <div class="body-medium" style="color: var(--text-secondary);">
                                @if($patient->x_ray_id)
                                    <div style="margin-bottom: var(--space-sm);">
                                        <strong>X-Ray ID:</strong> {{ $patient->x_ray_id }}
                                    </div>
                                @endif
                                @if($patient->images && is_array($patient->images))
                                    <div style="margin-bottom: var(--space-sm);">
                                        <strong>Images:</strong> {{ count($patient->images) }} files
                                    </div>
                                @endif
                                <div>
                                    <strong>Patient ID:</strong> {{ $patient->register_id }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Related Services -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Available Services</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Explore our comprehensive dental services
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">General Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Comprehensive oral health care including cleanings, fillings, and preventive treatments.
                </p>
                <a href="{{ route('services') }}" class="btn-apple-outline">Learn More</a>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Cosmetic Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Transform your smile with whitening, veneers, and advanced cosmetic procedures.
                </p>
                <a href="{{ route('services') }}" class="btn-apple-outline">Learn More</a>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Implant Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Replace missing teeth with durable, natural-looking dental implants.
                </p>
                <a href="{{ route('services') }}" class="btn-apple-outline">Learn More</a>
            </div>
        </div>
    </div>
</section>
@endsection