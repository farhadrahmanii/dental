@extends('layouts.app')

@section('title', __('dental.app_name') . ' - ' . __('dental.app_description'))
@section('description', __('dental.app_description'))

@section('content')
<!-- Apple-style Hero Section -->
<section class="hero-apple">
    <div class="container-apple">
        <div class="hero-content">
            <h1 class="display-large mb-4 animate-fade-in-up">
                {{ __('dental.hero_title') }}
            </h1>
            <p class="body-large mb-6 animate-fade-in-up" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                {{ __('dental.hero_subtitle') }}
            </p>
            <div class="animate-fade-in-up" style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
                <a href="#appointment" class="btn-apple">
                    {{ __('dental.book_appointment') }}
                </a>
                <a href="{{ route('services') }}" class="btn-apple-outline">
                    {{ __('dental.view_services') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Appointment Booking Section -->
<section id="appointment" class="section-apple" style="background: var(--surface);">
    <div class="container-apple-sm">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.book_your_appointment') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.schedule_consultation') }}
            </p>
        </div>
        
        <div class="card-apple-elevated" style="max-width: 600px; margin: 0 auto;">
            <div style="padding: var(--space-2xl);">
                <form id="appointmentForm" action="{{ route('appointments.store') }}" method="post">
                    @csrf
                    <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.full_name') }}</label>
                            <input type="text" name="name" id="patient_name" class="form-input-apple" placeholder="{{ __('dental.enter_full_name') }}" required>
                        </div>
                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.phone_number') }}</label>
                            <input type="tel" name="phone" id="patient_phone" class="form-input-apple" placeholder="{{ __('dental.enter_phone_number') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group-apple">
                        <label class="form-label-apple">{{ __('dental.email_address') }}</label>
                        <input type="email" name="email" id="patient_email" class="form-input-apple" placeholder="{{ __('dental.enter_email_address') }}" required>
                    </div>
                    
                    <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.service_selection') }}</label>
                            <select name="service" id="service_select" class="form-select-apple" required>
                                <option value="">{{ __('dental.select_service') }}</option>
                                <!-- Services will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="form-group-apple">
                            <label class="form-label-apple">{{ __('dental.preferred_date') }}</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-input-apple" required>
                        </div>
                    </div>
                    
                    <div class="form-group-apple">
                        <label class="form-label-apple">{{ __('dental.time_slot') }}</label>
                        <select name="appointment_time" id="appointment_time" class="form-select-apple" required disabled>
                            <option value="">{{ __('dental.select_date_first') }}</option>
                        </select>
                        <div id="time_slots_loading" style="display: none; margin-top: var(--space-sm);">
                            <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--text-secondary);">
                                <div style="width: 16px; height: 16px; border: 2px solid var(--primary); border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                <span class="body-small">{{ __('dental.loading_time_slots') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group-apple">
                        <label class="form-label-apple">{{ __('dental.additional_message') }}</label>
                        <textarea name="message" id="appointment_message" class="form-textarea-apple" placeholder="{{ __('dental.tell_us_needs') }}"></textarea>
                    </div>
                    
                    <button type="submit" id="submit_btn" class="btn-apple" style="width: 100%; padding: var(--space-md); font-size: 1rem;">
                        <span id="submit_text">{{ __('dental.book_appointment_btn') }}</span>
                        <div id="submit_loading" style="display: none;">
                            <div style="width: 20px; height: 20px; border: 2px solid var(--apple-white); border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                        </div>
                    </button>
                    
                    <!-- Success/Error Messages -->
                    <div id="appointment_message_container" style="margin-top: var(--space-lg); display: none;">
                        <div id="success_message" style="display: none; padding: var(--space-md); background: #d4edda; border: 1px solid #c3e6cb; border-radius: var(--radius-md); color: #155724;">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #28a745;">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="success_text"></span>
                            </div>
                        </div>
                        <div id="error_message" style="display: none; padding: var(--space-md); background: #f8d7da; border: 1px solid #f5c6cb; border-radius: var(--radius-md); color: #721c24;">
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #dc3545;">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                <span id="error_text"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.our_achievements') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.why_choose_description') }}
            </p>
        </div>
        
        <div class="grid-apple grid-apple-4">
            <div class="stat-apple">
                <div class="stat-number">{{ $totalPatients ?? 2500 }}+</div>
                <div class="stat-label">{{ __('dental.happy_patients') }}</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">15+</div>
                <div class="stat-label">{{ __('dental.years_experience') }}</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">50+</div>
                <div class="stat-label">{{ __('dental.successful_treatments') }}</div>
            </div>
            <div class="stat-apple">
                <div class="stat-number">100%</div>
                <div class="stat-label">{{ __('dental.awards_certifications') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Comprehensive Dental Services</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                From routine checkups to advanced procedures, we provide complete dental care
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
                <p class="body-medium" style="color: var(--text-secondary); margin-bottom: var(--space-lg);">
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
                <p class="body-medium" style="color: var(--text-secondary); margin-bottom: var(--space-lg);">
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
                <p class="body-medium" style="color: var(--text-secondary); margin-bottom: var(--space-lg);">
                    Replace missing teeth with durable, natural-looking dental implants.
                </p>
                <a href="{{ route('services') }}" class="btn-apple-outline">Learn More</a>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('services') }}" class="btn-apple">
                View All Services
            </a>
        </div>
    </div>
</section>

<!-- Patient Database Showcase -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Advanced Patient Management</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Our comprehensive patient database system ensures efficient care delivery
            </p>
        </div>
        
        <div class="grid-apple grid-apple-2">
            <div class="card-apple" style="padding: var(--space-xl);">
                <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <path d="M20 8v6M23 11l-3 3-3-3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-large">Patient Records</h3>
                        <p class="body-medium" style="color: var(--text-secondary);">Complete digital patient profiles</p>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Medical history tracking</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Treatment plan management</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Progress monitoring</span>
                    </li>
                </ul>
            </div>
            
            <div class="card-apple" style="padding: var(--space-xl);">
                <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27,6.96 12,12.01 20.73,6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="title-large">Digital Imaging</h3>
                        <p class="body-medium" style="color: var(--text-secondary);">Advanced X-ray management</p>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Digital X-ray storage</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Unique identification system</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Easy retrieval system</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('patients') }}" class="btn-apple">
                Explore Patient Database
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">What Our Patients Say</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Real experiences from our satisfied patients
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "DentalCare Pro transformed my smile completely. The staff is professional, the technology is amazing, and the results exceeded my expectations."
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="Sarah Johnson" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>Sarah Johnson</h6>
                        <p>Business Owner</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "The dental implant procedure was seamless and pain-free. The team's expertise and modern equipment made all the difference."
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="Michael Chen" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>Michael Chen</h6>
                        <p>Engineer</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "From routine cleanings to cosmetic procedures, DentalCare Pro has been my go-to dental clinic. The staff is friendly and professional."
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="Emily Rodriguez" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>Emily Rodriguez</h6>
                        <p>Teacher</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section-apple" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--apple-white);">
    <div class="container-apple text-center">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">Ready to Transform Your Smile?</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            Schedule your consultation today and let our experienced team create the perfect treatment plan for your dental needs.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary);">
                Book Appointment
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection