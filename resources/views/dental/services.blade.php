@extends('layouts.app')

@section('title', 'Dental Services - DentalCare Pro')
@section('description', 'Comprehensive dental services including general dentistry, cosmetic procedures, implants, and emergency care.')

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">Our Dental Services</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                Comprehensive dental care with modern technology and experienced professionals
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
                <h3 class="title-large mb-3">General Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Comprehensive oral health care including cleanings, fillings, root canals, and preventive treatments to maintain your dental health.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Regular Checkups</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Teeth Cleaning</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Fillings & Crowns</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Root Canal Therapy</span>
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
                <h3 class="title-large mb-3">Cosmetic Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Transform your smile with our advanced cosmetic procedures including whitening, veneers, and smile makeovers.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Teeth Whitening</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Porcelain Veneers</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Smile Makeover</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Bonding</span>
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
                <h3 class="title-large mb-3">Implant Dentistry</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Replace missing teeth with durable, natural-looking dental implants that restore both function and aesthetics.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Single Implants</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Multiple Implants</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Full Mouth Restoration</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Implant Crowns</span>
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
                <h3 class="title-large mb-3">Orthodontic Treatment</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Straighten teeth and correct bite issues with traditional braces, clear aligners, and modern orthodontic solutions.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Traditional Braces</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Clear Aligners</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Invisalign</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Retainers</span>
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
                <h3 class="title-large mb-3">Emergency Care</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    24/7 emergency dental care for urgent situations including severe pain, broken teeth, and dental trauma.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Tooth Pain Relief</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Broken Tooth Repair</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Lost Filling/Crown</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Dental Trauma</span>
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
                <h3 class="title-large mb-3">Digital Imaging</h3>
                <p class="body-medium mb-4" style="color: var(--text-secondary);">
                    Advanced digital X-rays and 3D imaging for accurate diagnosis and treatment planning with minimal radiation exposure.
                </p>
                <ul style="list-style: none; padding: 0; text-align: left;">
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Digital X-Rays</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">3D Imaging</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Panoramic Views</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-sm);">
                        <div style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-medium">Low Radiation</span>
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
                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="Dental Technology">
            </div>
            <div>
                <h2 class="headline-large mb-3">State-of-the-Art Equipment</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    We invest in the latest dental technology to provide you with the most accurate diagnoses and effective treatments available.
                </p>
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div class="stat-apple">
                        <div class="stat-number">100</div>
                        <div class="stat-label">Digital X-Ray Units</div>
                    </div>
                    <div class="stat-apple">
                        <div class="stat-number">50</div>
                        <div class="stat-label">Laser Systems</div>
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
                <h2 class="headline-large mb-3">How We Care For You</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    Our comprehensive process ensures you receive the best possible dental care from consultation to follow-up.
                </p>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Initial Consultation & Examination</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Digital Imaging & Diagnosis</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Customized Treatment Plan</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Professional Treatment Delivery</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Follow-up Care & Maintenance</span>
                    </li>
                </ul>
                <div class="mt-5">
                    <a href="#appointment" class="btn-apple">Book Consultation</a>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1606811971618-4486d14f3f99?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="Dental Process">
            </div>
        </div>
    </div>
</section>

<!-- Appointment CTA -->
<section class="section-apple" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--apple-white);">
    <div class="container-apple text-center">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">Ready to Transform Your Smile?</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            Schedule your consultation today and let our experienced team create the perfect treatment plan for your dental needs.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary);">
                Book Appointment
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection