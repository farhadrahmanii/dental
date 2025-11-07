@extends('layouts.app')

@section('title', __('dental.our_services') . ' - ' . __('dental.app_name'))
@section('description', __('dental.services_subtitle'))

@section('content')
<style>
    .service-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }
    
    .service-card:hover::before {
        transform: scaleX(1);
    }
    
    .service-icon {
        transition: all 0.3s ease;
    }
    
    .service-card:hover .service-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .process-step {
        position: relative;
        padding-left: var(--space-xl);
        transition: all 0.3s ease;
    }
    
    .process-step::before {
        content: counter(step-counter);
        counter-increment: step-counter;
        position: absolute;
        left: 0;
        top: 0;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--apple-white);
        font-weight: 600;
        font-size: 1.125rem;
    }
    
    .process-list {
        counter-reset: step-counter;
    }
    
    .process-step:hover {
        transform: translateX(8px);
    }
    
    .stat-card {
        transition: all 0.3s ease;
        padding: var(--space-lg);
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .service-card:nth-child(1) { animation-delay: 0.1s; }
    .service-card:nth-child(2) { animation-delay: 0.2s; }
    .service-card:nth-child(3) { animation-delay: 0.3s; }
    .service-card:nth-child(4) { animation-delay: 0.4s; }
    .service-card:nth-child(5) { animation-delay: 0.5s; }
    .service-card:nth-child(6) { animation-delay: 0.6s; }
    
    @media (max-width: 768px) {
        .service-card:hover {
            transform: none;
        }
    }
</style>

<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: linear-gradient(135deg, rgba(0, 122, 255, 0.05) 0%, rgba(0, 81, 213, 0.05) 100%);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3 animate-fade-in-up">{{ __('dental.our_services') }}</h1>
            <p class="body-large animate-fade-in-up" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                {{ __('dental.services_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple grid-apple-3">
            <!-- General Dentistry -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.general_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.general_dentistry_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.regular_checkups') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.teeth_cleaning') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.fillings_crowns') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.root_canal_therapy') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Cosmetic Dentistry -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #FF6B9D 0%, #C44569 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.cosmetic_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.cosmetic_dentistry_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.teeth_whitening') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.porcelain_veneers') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.smile_makeover') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.bonding') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Implant Dentistry -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2v20M2 12h20M12 2l8 8-8 8-8-8 8-8z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.implant_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.implant_dentistry_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.single_implants') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.multiple_implants') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.full_mouth_restoration') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.implant_crowns') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Orthodontic Treatment -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #A8E6CF 0%, #7FCDCD 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.orthodontic_treatment') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.orthodontic_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.traditional_braces') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.clear_aligners') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.invisalign') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.retainers') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Emergency Care -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #FF6B6B 0%, #EE5A6F 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.emergency_care') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.emergency_care_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.tooth_pain_relief') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.broken_tooth_repair') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.lost_filling_crown') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.dental_trauma') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Digital Imaging -->
            <div class="card-apple service-card fade-in-up" style="text-align: center; padding: var(--space-xl);">
                <div class="service-icon" style="width: 72px; height: 72px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg); box-shadow: var(--shadow-md);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.digital_imaging') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary); min-height: 60px;">
                    {{ __('dental.digital_imaging_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.digital_xrays') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.3d_imaging') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.panoramic_views') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="body-medium">{{ __('dental.low_radiation') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Technology Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div style="order: 2;">
                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.state_of_art_equipment') }}" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);">
            </div>
            <div style="order: 1;">
                <h2 class="headline-large mb-3">{{ __('dental.state_of_art_equipment') }}</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    {{ __('dental.technology_desc') }}
                </p>
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div class="stat-card">
                        <div class="stat-number" style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin-bottom: var(--space-xs);">100+</div>
                        <div class="stat-label" style="color: var(--text-secondary); font-size: 0.875rem;">{{ __('dental.digital_xray_units') }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin-bottom: var(--space-xs);">50+</div>
                        <div class="stat-label" style="color: var(--text-secondary); font-size: 0.875rem;">{{ __('dental.laser_systems') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div>
                <h2 class="headline-large mb-3">{{ __('dental.how_we_care') }}</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    {{ __('dental.process_desc') }}
                </p>
                <ul class="process-list" style="list-style: none; padding: 0;">
                    <li class="process-step" style="margin-bottom: var(--space-lg);">
                        <span class="body-large" style="display: block; font-weight: 500;">{{ __('dental.initial_consultation') }}</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; display: block; margin-top: var(--space-xs);">Comprehensive examination and discussion</span>
                    </li>
                    <li class="process-step" style="margin-bottom: var(--space-lg);">
                        <span class="body-large" style="display: block; font-weight: 500;">{{ __('dental.digital_imaging_diagnosis') }}</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; display: block; margin-top: var(--space-xs);">Advanced imaging for accurate diagnosis</span>
                    </li>
                    <li class="process-step" style="margin-bottom: var(--space-lg);">
                        <span class="body-large" style="display: block; font-weight: 500;">{{ __('dental.customized_treatment_plan') }}</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; display: block; margin-top: var(--space-xs);">Personalized plan tailored to your needs</span>
                    </li>
                    <li class="process-step" style="margin-bottom: var(--space-lg);">
                        <span class="body-large" style="display: block; font-weight: 500;">{{ __('dental.professional_treatment_delivery') }}</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; display: block; margin-top: var(--space-xs);">Expert care with modern techniques</span>
                    </li>
                    <li class="process-step" style="margin-bottom: var(--space-lg);">
                        <span class="body-large" style="display: block; font-weight: 500;">{{ __('dental.follow_up_care') }}</span>
                        <span style="color: var(--text-secondary); font-size: 0.875rem; display: block; margin-top: var(--space-xs);">Ongoing support and maintenance</span>
                    </li>
                </ul>
                <div class="mt-5">
                    <a href="{{ route('home') }}#appointment" class="btn-apple">{{ __('dental.book_consultation') }}</a>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1606811971618-4486d14f3f99?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.how_we_care') }}" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);">
            </div>
        </div>
    </div>
</section>

<!-- Appointment CTA -->
<section class="section-apple" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--apple-white); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23ffffff\" fill-opacity=\"1\"><path d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/></g></g></svg>');"></div>
    <div class="container-apple text-center" style="position: relative; z-index: 1;">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">{{ __('dental.ready_transform_smile') }}</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            {{ __('dental.cta_subtitle') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary); transition: all 0.3s ease;">
                {{ __('dental.book_appointment') }}
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white); transition: all 0.3s ease;">
                {{ __('dental.contact_us') }}
            </a>
        </div>
    </div>
</section>

<script>
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in-up').forEach(el => {
        observer.observe(el);
    });
</script>
@endsection
