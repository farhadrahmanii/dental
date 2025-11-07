@extends('layouts.app')

@section('title', __('dental.our_services') . ' - ' . __('dental.app_name'))
@section('description', __('dental.services_subtitle'))

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">{{ __('dental.our_services') }}</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
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
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.general_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.general_dentistry_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.regular_checkups') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.teeth_cleaning') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.fillings_crowns') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.root_canal_therapy') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Cosmetic Dentistry -->
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.cosmetic_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.cosmetic_dentistry_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.teeth_whitening') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.porcelain_veneers') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.smile_makeover') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.bonding') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Implant Dentistry -->
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.implant_dentistry') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.implant_dentistry_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.single_implants') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.multiple_implants') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.full_mouth_restoration') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.implant_crowns') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Orthodontic Treatment -->
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.orthodontic_treatment') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.orthodontic_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.traditional_braces') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.clear_aligners') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.invisalign') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.retainers') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Emergency Care -->
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.emergency_care') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.emergency_care_desc') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.tooth_pain_relief') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.broken_tooth_repair') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.lost_filling_crown') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.dental_trauma') }}</span>
                    </li>
                </ul>
            </div>

            <!-- Digital Imaging -->
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">{{ __('dental.digital_imaging') }}</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    {{ __('dental.digital_imaging_desc_full') }}
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.digital_xrays') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.3d_imaging') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">{{ __('dental.panoramic_views') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
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
            <div>
                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.state_of_art_equipment') }}">
            </div>
            <div>
                <h2 class="headline-large mb-3">{{ __('dental.state_of_art_equipment') }}</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    {{ __('dental.technology_desc') }}
                </p>
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div class="stat-apple">
                        <div class="stat-number">100</div>
                        <div class="stat-label">{{ __('dental.digital_xray_units') }}</div>
                    </div>
                    <div class="stat-apple">
                        <div class="stat-number">50</div>
                        <div class="stat-label">{{ __('dental.laser_systems') }}</div>
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
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.initial_consultation') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.digital_imaging_diagnosis') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.customized_treatment_plan') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.professional_treatment_delivery') }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">{{ __('dental.follow_up_care') }}</span>
                    </li>
                </ul>
                <div class="mt-5">
                    <a href="{{ route('home') }}#appointment" class="btn-apple">{{ __('dental.book_consultation') }}</a>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1606811971618-4486d14f3f99?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="{{ __('dental.how_we_care') }}">
            </div>
        </div>
    </div>
</section>

<!-- Appointment CTA -->
<section class="section-apple" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--apple-white);">
    <div class="container-apple text-center">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">{{ __('dental.ready_transform_smile') }}</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            {{ __('dental.cta_subtitle') }}
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary);">
                {{ __('dental.book_appointment') }}
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                {{ __('dental.contact_us') }}
            </a>
        </div>
    </div>
</section>
@endsection