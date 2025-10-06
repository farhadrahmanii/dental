@extends('layouts.app')

@section('title', 'Contact Us - DentalCare Pro')
@section('description', 'Get in touch with DentalCare Pro. Contact us for appointments, questions, or emergency dental care.')

@section('content')
<!-- Apple-style Page Header -->
<section class="section-apple-sm" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center">
            <h1 class="display-medium mb-3">Contact Us</h1>
            <p class="body-large" style="color: var(--text-secondary); max-width: 600px; margin-left: auto; margin-right: auto;">
                Get in touch with our dental care team. We're here to help with all your dental needs.
            </p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 2fr 1fr; gap: var(--space-2xl);">
            <!-- Contact Form -->
            <div class="card-apple-elevated">
                <div style="padding: var(--space-2xl);">
                    <h2 class="headline-medium mb-5">Send us a Message</h2>
                    <form id="contactForm" action="#" method="post">
                        @csrf
                        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                            <div class="form-group-apple">
                                <label class="form-label-apple">Full Name</label>
                                <input type="text" name="name" class="form-input-apple" placeholder="Enter your full name" required>
                            </div>
                            <div class="form-group-apple">
                                <label class="form-label-apple">Email Address</label>
                                <input type="email" name="email" class="form-input-apple" placeholder="Enter your email address" required>
                            </div>
                        </div>
                        
                        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                            <div class="form-group-apple">
                                <label class="form-label-apple">Phone Number</label>
                                <input type="tel" name="phone" class="form-input-apple" placeholder="Enter your phone number" required>
                            </div>
                            <div class="form-group-apple">
                                <label class="form-label-apple">Service</label>
                                <select name="service" class="form-select-apple" required>
                                    <option value="">Select a service</option>
                                    <option value="general">General Dentistry</option>
                                    <option value="cosmetic">Cosmetic Dentistry</option>
                                    <option value="implant">Implant Dentistry</option>
                                    <option value="orthodontic">Orthodontic Treatment</option>
                                    <option value="emergency">Emergency Care</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group-apple">
                            <label class="form-label-apple">Message</label>
                            <textarea name="message" class="form-textarea-apple" placeholder="Tell us about your dental needs or any specific concerns..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-apple" style="width: 100%; padding: var(--space-md); font-size: 1rem;">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <div class="card-apple" style="padding: var(--space-xl); margin-bottom: var(--space-lg);">
                    <h3 class="title-large mb-4">Get in Touch</h3>
                    
                    <div style="margin-bottom: var(--space-lg);">
                        <div style="display: flex; align-items: start; gap: var(--space-md);">
                            <div style="width: 24px; height: 24px; color: var(--primary); margin-top: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="title-medium mb-1">Address</h4>
                                <p class="body-medium" style="color: var(--text-secondary);">
                                    123 Dental Street<br>
                                    Health City, HC 12345<br>
                                    United States
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: var(--space-lg);">
                        <div style="display: flex; align-items: start; gap: var(--space-md);">
                            <div style="width: 24px; height: 24px; color: var(--primary); margin-top: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="title-medium mb-1">Phone</h4>
                                <p class="body-medium" style="color: var(--text-secondary);">
                                    <a href="tel:+15551234567" style="color: var(--primary); text-decoration: none;">+1 (555) 123-4567</a><br>
                                    <small style="color: var(--text-tertiary);">Emergency: +1 (555) 123-4568</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: var(--space-lg);">
                        <div style="display: flex; align-items: start; gap: var(--space-md);">
                            <div style="width: 24px; height: 24px; color: var(--primary); margin-top: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="title-medium mb-1">Email</h4>
                                <p class="body-medium" style="color: var(--text-secondary);">
                                    <a href="mailto:info@dentalcarepro.com" style="color: var(--primary); text-decoration: none;">info@dentalcarepro.com</a><br>
                                    <a href="mailto:emergency@dentalcarepro.com" style="color: var(--primary); text-decoration: none;">emergency@dentalcarepro.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: start; gap: var(--space-md);">
                            <div style="width: 24px; height: 24px; color: var(--primary); margin-top: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12,6 12,12 16,14"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="title-medium mb-1">Office Hours</h4>
                                <div class="body-medium" style="color: var(--text-secondary);">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-xs);">
                                        <span>Monday - Friday:</span>
                                        <span>9:00 AM - 5:00 PM</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-xs);">
                                        <span>Saturday:</span>
                                        <span>10:00 AM - 2:00 PM</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Sunday:</span>
                                        <span>Closed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="card-apple" style="padding: var(--space-xl);">
                    <h3 class="title-large mb-4">Follow Us</h3>
                    <div style="display: flex; gap: var(--space-md);">
                        <a href="#" style="width: 40px; height: 40px; background: var(--primary); color: var(--apple-white); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" style="width: 40px; height: 40px; background: var(--primary); color: var(--apple-white); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" style="width: 40px; height: 40px; background: var(--primary); color: var(--apple-white); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section-apple" style="background: var(--surface);">
    <div class="container-apple">
        <div class="text-center mb-6">
            <h2 class="headline-large mb-3">Find Us</h2>
            <p class="body-large" style="color: var(--text-secondary);">
                Visit our modern dental clinic in the heart of the city
            </p>
        </div>
        
        <div class="card-apple-elevated">
            <div style="padding: var(--space-xl); text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--apple-white);">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h3 class="headline-medium mb-3">Interactive Map</h3>
                <p class="body-large mb-6" style="color: var(--text-secondary);">
                    123 Dental Street, Health City, HC 12345
                </p>
                <a href="https://maps.google.com" target="_blank" class="btn-apple">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    Open in Google Maps
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-apple">
    <div class="container-apple">
        <div class="grid-apple" style="grid-template-columns: 1fr 1fr; gap: var(--space-2xl); align-items: center;">
            <div>
                <h2 class="headline-large mb-3">Frequently Asked Questions</h2>
                <p class="body-large mb-5" style="color: var(--text-secondary);">
                    Common questions about our services, insurance, and appointment process.
                </p>
                <ul style="list-style: none; padding: 0;">
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Do you accept insurance?</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">What payment methods do you accept?</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">How do I schedule an appointment?</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">Do you offer emergency services?</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
                        <div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>
                        <span class="body-large">What should I bring to my first visit?</span>
                    </li>
                </ul>
                <div class="mt-5">
                    <a href="tel:+15551234567" class="btn-apple">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Call Us Now
                    </a>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80" class="img-medium img-optimized" alt="Dental FAQ">
            </div>
        </div>
    </div>
</section>

<!-- Emergency Contact -->
<section class="section-apple" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: var(--apple-white);">
    <div class="container-apple text-center">
        <h2 class="headline-large mb-3" style="color: var(--apple-white);">Dental Emergency?</h2>
        <p class="body-large mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
            We provide 24/7 emergency dental care for urgent situations. Don't wait - call us immediately.
        </p>
        <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
            <a href="tel:+15551234568" class="btn-apple" style="background: var(--apple-white); color: #dc3545;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                Emergency Line
            </a>
            <a href="{{ route('home') }}#appointment" class="btn-apple-outline" style="border-color: var(--apple-white); color: var(--apple-white);">
                Book Appointment
            </a>
        </div>
    </div>
</section>
@endsection