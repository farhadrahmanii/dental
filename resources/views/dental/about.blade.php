@extends('layouts.app')

@section('title', __('dental.about_dentalcare_pro') . ' - ' . __('dental.app_name'))
@section('description', __('dental.dedicated_exceptional_care'))

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">{{ __('dental.about_dentalcare_pro') }}</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                {{ __('dental.dedicated_exceptional_care') }}
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div>
                <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.dental_mission_alt') }}">
            </div>
            <div>
                <h2 class="headline-large mb-3">{{ __('dental.our_mission_title') }}</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    {{ __('dental.mission_description') }}
                </p>
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div class="stat-apple">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">{{ __('dental.years_experience_label') }}</div>
                    </div>
                    <div class="stat-apple">
                        <div class="stat-number">2500+</div>
                        <div class="stat-label">{{ __('dental.happy_patients_label') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.our_core_values') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.core_values_description') }}
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.excellence_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.excellence_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <path d="M20 8v6M23 11l-3 3-3-3"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.compassion_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.compassion_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.innovation_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.innovation_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.meet_our_team_title') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.experienced_professionals') }}
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="{{ __('dental.dr_sarah_johnson') }}" class="img-team">
                <h3 class="title-large mb-2">{{ __('dental.dr_sarah_johnson') }}</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">{{ __('dental.dr_sarah_johnson_title') }}</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.dr_sarah_johnson_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="{{ __('dental.dr_michael_chen') }}" class="img-team">
                <h3 class="title-large mb-2">{{ __('dental.dr_michael_chen') }}</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">{{ __('dental.dr_michael_chen_title') }}</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.dr_michael_chen_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1594824388852-9a0b8b7b0b8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="{{ __('dental.dr_emily_rodriguez') }}" class="img-team">
                <h3 class="title-large mb-2">{{ __('dental.dr_emily_rodriguez') }}</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">{{ __('dental.dr_emily_rodriguez_title') }}</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.dr_emily_rodriguez_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Technology Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div>
                <h2 class="headline-large mb-3">{{ __('dental.advanced_technology_title') }}</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    {{ __('dental.advanced_technology_description') }}
                </p>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.digital_xray_systems') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.3d_imaging_technology') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.laser_dentistry') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.cad_cam_technology') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.intraoral_cameras') }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.dental_technology_alt') }}">
            </div>
        </div>
    </div>
</section>

<!-- Patient Care Process -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.our_patient_care_process') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.care_process_description') }}
            </p>
        </div>
        
        <div class="grid-apple" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-xl);">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">1</span>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.initial_consultation_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.initial_consultation_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">2</span>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.treatment_planning_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.treatment_planning_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">3</span>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.treatment_delivery_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.treatment_delivery_description') }}
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">4</span>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.follow_up_care_title') }}</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    {{ __('dental.follow_up_care_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">{{ __('dental.what_patients_say') }}</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                {{ __('dental.testimonials_subtitle') }}
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "{{ __('dental.testimonial_1_quote') }}"
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="{{ __('dental.sarah_johnson') }}" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>{{ __('dental.sarah_johnson') }}</h6>
                        <p>{{ __('dental.business_owner') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "{{ __('dental.testimonial_2_quote') }}"
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="{{ __('dental.michael_chen') }}" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>{{ __('dental.michael_chen') }}</h6>
                        <p>{{ __('dental.engineer') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-apple">
                <div class="testimonial-content">
                    "{{ __('dental.testimonial_3_quote') }}"
                </div>
                <div class="testimonial-author">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=150&q=80" alt="{{ __('dental.emily_rodriguez') }}" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h6>{{ __('dental.emily_rodriguez') }}</h6>
                        <p>{{ __('dental.teacher') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section-apple" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--apple-white);">
    <div class="container-apple text-center">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">{{ __('dental.ready_experience_care') }}</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            {{ __('dental.experience_care_description') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary);">
                {{ __('dental.book_consultation_label') }}
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                {{ __('dental.contact_us') }}
            </a>
        </div>
    </div>
</section>
@endsection