@extends('layouts.app')

@section('title', 'About Us - DentalCare Pro')
@section('description', 'Learn about DentalCare Pro - our mission, experienced team, and commitment to providing exceptional dental care.')

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">About DentalCare Pro</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                Dedicated to providing exceptional dental care with modern technology and compassionate service
            </p>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div>
                <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="Dental Mission">
            </div>
            <div>
                <h2 class="headline-large mb-3">Our Mission</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    At DentalCare Pro, we are committed to providing exceptional dental care that combines advanced technology with personalized service. Our mission is to help every patient achieve optimal oral health and a beautiful smile they can be proud of.
                </p>
                <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div class="stat-apple">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                    <div class="stat-apple">
                        <div class="stat-number">2500+</div>
                        <div class="stat-label">Happy Patients</div>
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
            <h2 class="headline-large mb-3">Our Core Values</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                The principles that guide everything we do
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Excellence</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    We strive for excellence in every aspect of dental care, from diagnosis to treatment and follow-up care.
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
                <h3 class="title-large mb-3">Compassion</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    We treat every patient with empathy, understanding, and respect, ensuring a comfortable and positive experience.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="title-large mb-3">Innovation</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    We embrace the latest dental technologies and techniques to provide the most effective and comfortable treatments.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Meet Our Team</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Experienced professionals dedicated to your dental health
            </p>
        </div>
        
        <div class="grid-apple grid-apple-3">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="Dr. Sarah Johnson" class="img-team">
                <h3 class="title-large mb-2">Dr. Sarah Johnson</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">Chief Dental Officer</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    With over 15 years of experience in general and cosmetic dentistry, Dr. Johnson leads our team with expertise and compassion.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="Dr. Michael Chen" class="img-team">
                <h3 class="title-large mb-2">Dr. Michael Chen</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">Implant Specialist</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Specializing in dental implants and oral surgery, Dr. Chen brings advanced techniques and precision to every procedure.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <img src="https://images.unsplash.com/photo-1594824388852-9a0b8b7b0b8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" alt="Dr. Emily Rodriguez" class="img-team">
                <h3 class="title-large mb-2">Dr. Emily Rodriguez</h3>
                <p class="body-medium mb-3" style="color: var(--primary); font-weight: 600;">Orthodontist</p>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Dr. Rodriguez specializes in orthodontic treatments, helping patients achieve perfectly aligned smiles with modern techniques.
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
                <h2 class="headline-large mb-3">Advanced Technology</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    We invest in the latest dental technology to provide accurate diagnoses, comfortable treatments, and superior results for our patients.
                </p>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Digital X-Ray Systems</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">3D Imaging Technology</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Laser Dentistry</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">CAD/CAM Technology</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Intraoral Cameras</span>
                    </li>
                </ul>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="Dental Technology">
            </div>
        </div>
    </div>
</section>

<!-- Patient Care Process -->
<section class="section-apple">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Our Patient Care Process</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                A comprehensive approach to dental care that puts your comfort and health first
            </p>
        </div>
        
        <div class="grid-apple" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-xl);">
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">1</span>
                </div>
                <h3 class="title-large mb-3">Initial Consultation</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Comprehensive examination and discussion of your dental health goals and concerns.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">2</span>
                </div>
                <h3 class="title-large mb-3">Treatment Planning</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Customized treatment plan developed based on your specific needs and preferences.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">3</span>
                </div>
                <h3 class="title-large mb-3">Treatment Delivery</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Professional treatment using advanced techniques and technology for optimal results.
                </p>
            </div>
            
            <div class="card-apple" style="text-align: center; padding: var(--space-xl);">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <span style="color: var(--apple-white); font-weight: 600; font-size: 1.25rem;">4</span>
                </div>
                <h3 class="title-large mb-3">Follow-up Care</h3>
                <p class="body-medium" style="color: var(--text-secondary);">
                    Ongoing support and maintenance to ensure long-term dental health and satisfaction.
                </p>
            </div>
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
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">Ready to Experience Exceptional Dental Care?</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            Schedule your consultation today and discover why thousands of patients trust DentalCare Pro with their dental health.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}#appointment" class="btn-apple" style="background: var(--apple-white); color: var(--primary);">
                Book Consultation
            </a>
            <a href="{{ route('contact') }}" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection