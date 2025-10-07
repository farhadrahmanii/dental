<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DentalCare Pro - Professional Dental Services')</title>
    <meta name="description" content="@yield('description', 'Professional dental care with modern technology and experienced doctors. Book your appointment today.')">
    
    <!-- Apple-style Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;500;600;700&family=SF+Pro+Text:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Apple-style CSS -->
    <style>
        :root {
            /* Apple Color Palette */
            --apple-blue: #007AFF;
            --apple-blue-dark: #0051D5;
            --apple-gray-1: #F2F2F7;
            --apple-gray-2: #E5E5EA;
            --apple-gray-3: #D1D1D6;
            --apple-gray-4: #C7C7CC;
            --apple-gray-5: #AEAEB2;
            --apple-gray-6: #8E8E93;
            --apple-gray-7: #636366;
            --apple-gray-8: #48484A;
            --apple-gray-9: #3A3A3C;
            --apple-gray-10: #1C1C1E;
            --apple-white: #FFFFFF;
            --apple-black: #000000;
            
            /* Semantic Colors */
            --primary: var(--apple-blue);
            --primary-dark: var(--apple-blue-dark);
            --background: var(--apple-gray-1);
            --surface: var(--apple-white);
            --surface-secondary: var(--apple-gray-2);
            --text-primary: var(--apple-gray-10);
            --text-secondary: var(--apple-gray-7);
            --text-tertiary: var(--apple-gray-6);
            --border: var(--apple-gray-3);
            --border-light: var(--apple-gray-2);
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 16px 48px rgba(0, 0, 0, 0.15);
            
            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            --space-3xl: 4rem;
            --space-4xl: 6rem;
            
            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-2xl: 20px;
            --radius-full: 9999px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.5;
            font-size: 16px;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Apple-style Typography */
        .display-large {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .display-medium {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .headline-large {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 600;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .headline-medium {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(1.25rem, 2.5vw, 1.75rem);
            font-weight: 600;
            line-height: 1.3;
        }

        .title-large {
            font-size: clamp(1.125rem, 2vw, 1.375rem);
            font-weight: 600;
            line-height: 1.4;
        }

        .title-medium {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .body-large {
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .body-medium {
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .label-large {
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .label-medium {
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Apple-style Navigation */
        .nav-apple {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-apple.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-sm);
        }

        .nav-brand {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.01em;
        }

        .nav-link-apple {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link-apple:hover,
        .nav-link-apple.active {
            color: var(--primary);
            background-color: rgba(0, 122, 255, 0.1);
        }

        .btn-apple {
            background: var(--primary);
            color: var(--apple-white);
            border: none;
            border-radius: var(--radius-lg);
            padding: var(--space-sm) var(--space-lg);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-xs);
            box-shadow: var(--shadow-sm);
        }

        .btn-apple:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-apple:active {
            transform: translateY(0);
        }

        .btn-apple-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: var(--radius-lg);
            padding: var(--space-sm) var(--space-lg);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-xs);
        }

        .btn-apple-outline:hover {
            background: var(--primary);
            color: var(--apple-white);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Apple-style Cards */
        .card-apple {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-apple:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--border);
        }

        .card-apple-elevated {
            background: var(--surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .card-apple-elevated:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        /* Apple-style Forms */
        .form-group-apple {
            margin-bottom: var(--space-lg);
        }

        .form-label-apple {
            display: block;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: var(--space-sm);
            font-size: 0.875rem;
        }

        .form-input-apple {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 400;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input-apple:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .form-input-apple::placeholder {
            color: var(--text-tertiary);
        }

        .form-select-apple {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 400;
            transition: all 0.2s ease;
            outline: none;
            cursor: pointer;
        }

        .form-select-apple:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .form-textarea-apple {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface);
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 400;
            transition: all 0.2s ease;
            outline: none;
            resize: vertical;
            min-height: 120px;
        }

        .form-textarea-apple:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        /* Apple-style Sections */
        .section-apple {
            padding: var(--space-4xl) 0;
        }

        .section-apple-sm {
            padding: var(--space-2xl) 0;
        }

        .container-apple {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        .container-apple-sm {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        /* Apple-style Grid */
        .grid-apple {
            display: grid;
            gap: var(--space-xl);
        }

        .grid-apple-2 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .grid-apple-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .grid-apple-4 {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        /* Apple-style Hero */
        .hero-apple {
            background: linear-gradient(135deg, var(--apple-gray-1) 0%, var(--apple-white) 100%);
            padding: var(--space-4xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-apple::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(0, 122, 255, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(0, 122, 255, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        /* Apple-style Stats */
        .stat-apple {
            text-align: center;
            padding: var(--space-xl);
        }

        .stat-number {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: var(--space-sm);
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.875rem;
        }

        /* Apple-style Testimonials */
        .testimonial-apple {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .testimonial-apple:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .testimonial-content {
            font-style: italic;
            color: var(--text-secondary);
            margin-bottom: var(--space-lg);
            line-height: 1.6;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .testimonial-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-full);
            object-fit: cover;
        }

        .testimonial-info h6 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-xs);
        }

        .testimonial-info p {
            color: var(--text-tertiary);
            font-size: 0.875rem;
        }

        /* Apple-style Footer */
        .footer-apple {
            background: var(--apple-gray-10);
            color: var(--apple-white);
            padding: var(--space-4xl) 0 var(--space-xl);
        }

        .footer-apple .nav-brand {
            color: var(--apple-white);
        }

        .footer-apple .nav-link-apple {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-apple .nav-link-apple:hover {
            color: var(--apple-white);
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Apple-style Animations */
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* Apple-style Utilities */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: var(--space-xs); }
        .mb-2 { margin-bottom: var(--space-sm); }
        .mb-3 { margin-bottom: var(--space-md); }
        .mb-4 { margin-bottom: var(--space-lg); }
        .mb-5 { margin-bottom: var(--space-xl); }
        .mb-6 { margin-bottom: var(--space-2xl); }

        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: var(--space-xs); }
        .mt-2 { margin-top: var(--space-sm); }
        .mt-3 { margin-top: var(--space-md); }
        .mt-4 { margin-top: var(--space-lg); }
        .mt-5 { margin-top: var(--space-xl); }
        .mt-6 { margin-top: var(--space-2xl); }

        .p-0 { padding: 0; }
        .p-1 { padding: var(--space-xs); }
        .p-2 { padding: var(--space-sm); }
        .p-3 { padding: var(--space-md); }
        .p-4 { padding: var(--space-lg); }
        .p-5 { padding: var(--space-xl); }
        .p-6 { padding: var(--space-2xl); }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container-apple,
            .container-apple-sm {
                padding: 0 var(--space-md);
            }
            
            .section-apple {
                padding: var(--space-2xl) 0;
            }
            
            .grid-apple-2,
            .grid-apple-3,
            .grid-apple-4 {
                grid-template-columns: 1fr;
            }
            
            .hero-apple {
                padding: var(--space-2xl) 0;
            }
        }

        /* Apple-style Patient Cards */
        .patient-card-apple {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            padding: var(--space-xl);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .patient-card-apple:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        /* Enhanced Image Styles */
        .img-optimized {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-lg);
            transition: all 0.3s ease;
        }

        .img-optimized:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-md);
        }

        .img-medium {
            width: 100%;
            max-width: 400px;
            height: 250px;
            object-fit: cover;
            border-radius: var(--radius-lg);
        }

        .img-small {
            width: 100%;
            max-width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: var(--radius-md);
        }

        .img-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: var(--radius-full);
            border: 2px solid var(--border-light);
        }

        .img-team {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: var(--radius-full);
            border: 3px solid var(--surface);
            box-shadow: var(--shadow-sm);
        }

        .patient-header {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .patient-avatar {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--apple-white);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .patient-info h5 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-xs);
        }

        .patient-id {
            color: var(--text-tertiary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .patient-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .patient-detail {
            text-align: center;
        }

        .patient-detail-label {
            color: var(--text-tertiary);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: var(--space-xs);
        }

        .patient-detail-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Apple-style Search */
        .search-apple {
            position: relative;
            margin-bottom: var(--space-xl);
        }

        .search-input-apple {
            width: 100%;
            padding: var(--space-md) var(--space-md) var(--space-md) 48px;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            background: var(--surface);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.2s ease;
            outline: none;
        }

        .search-input-apple:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .search-icon {
            position: absolute;
            left: var(--space-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            font-size: 1.125rem;
        }

        /* Apple-style Badges */
        .badge-apple {
            display: inline-flex;
            align-items: center;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-primary {
            background: rgba(0, 122, 255, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background: rgba(52, 199, 89, 0.1);
            color: #34C759;
        }

        .badge-warning {
            background: rgba(255, 149, 0, 0.1);
            color: #FF9500;
        }

        .badge-danger {
            background: rgba(255, 59, 48, 0.1);
            color: #FF3B30;
        }

        /* Apple-style Loading */
        .loading-apple {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Enhanced UI/UX Features */
        .form-group-apple {
            position: relative;
        }

        .form-group-apple.focused .form-label-apple {
            color: var(--primary);
            transform: translateY(-2px);
        }

        .form-input-apple:focus + .form-label-apple,
        .form-select-apple:focus + .form-label-apple,
        .form-textarea-apple:focus + .form-label-apple {
            color: var(--primary);
        }

        .btn-apple:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-apple:disabled:hover {
            transform: none !important;
            box-shadow: var(--shadow-sm);
        }

        /* Enhanced Card Interactions */
        .card-apple {
            position: relative;
            overflow: hidden;
        }

        .card-apple::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .card-apple:hover::before {
            left: 100%;
        }

        /* Enhanced Navigation */
        .nav-link-apple {
            position: relative;
            overflow: hidden;
        }

        .nav-link-apple::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link-apple:hover::after,
        .nav-link-apple.active::after {
            width: 80%;
        }

        /* Enhanced Search */
        .search-apple {
            position: relative;
        }

        .search-apple .search-input-apple:focus + .search-icon {
            color: var(--primary);
        }

        /* Enhanced Stats */
        .stat-apple {
            position: relative;
            overflow: hidden;
        }

        .stat-apple::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.05) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-apple:hover::before {
            opacity: 1;
        }

        /* Enhanced Testimonials */
        .testimonial-apple {
            position: relative;
        }

        .testimonial-apple::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.1;
            font-family: serif;
        }

        /* Enhanced Patient Cards */
        .patient-card-apple {
            position: relative;
            overflow: hidden;
        }

        .patient-card-apple .patient-avatar {
            transition: all 0.3s ease;
        }

        .patient-card-apple:hover .patient-avatar {
            transform: scale(1.1);
        }

        /* Enhanced Forms */
        .form-input-apple,
        .form-select-apple,
        .form-textarea-apple {
            position: relative;
        }

        .form-input-apple:focus,
        .form-select-apple:focus,
        .form-textarea-apple:focus {
            transform: translateY(-1px);
        }

        /* Enhanced Buttons */
        .btn-apple,
        .btn-apple-outline {
            position: relative;
            overflow: hidden;
        }

        .btn-apple::before,
        .btn-apple-outline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .btn-apple:active::before,
        .btn-apple-outline:active::before {
            width: 300px;
            height: 300px;
        }

        /* Enhanced Modal */
        .modal-apple {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .modal-content-apple {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Enhanced Pagination Styles */
        .pagination-apple {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }

        .pagination-apple .pagination {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination-apple .pagination li {
            margin: 0;
        }

        .pagination-apple .pagination li a,
        .pagination-apple .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: var(--space-sm);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: var(--surface);
        }

        .pagination-apple .pagination li a:hover {
            background: var(--primary);
            color: var(--apple-white);
            border-color: var(--primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .pagination-apple .pagination li span {
            background: var(--primary);
            color: var(--apple-white);
            border-color: var(--primary);
        }

        .pagination-apple .pagination li.disabled a,
        .pagination-apple .pagination li.disabled span {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--apple-gray-2);
            color: var(--text-tertiary);
        }

        .pagination-apple .pagination li.disabled a:hover {
            transform: none;
            box-shadow: none;
            background: var(--apple-gray-2);
            color: var(--text-tertiary);
            border-color: var(--border-light);
        }

        /* Enhanced Filter Tags */
        .filter-tag {
            display: inline-flex;
            align-items: center;
            padding: var(--space-xs) var(--space-sm);
            background: rgba(0, 122, 255, 0.1);
            color: var(--primary);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid rgba(0, 122, 255, 0.2);
        }

        .filter-tag button {
            margin-left: var(--space-xs);
            padding: 0;
            font-size: 1rem;
            line-height: 1;
        }

        .filter-tag button:hover {
            color: var(--primary-dark);
        }

        /* Enhanced Patient Cards */
        .enhanced-patient-card {
            position: relative;
            transition: all 0.3s ease;
        }

        .enhanced-patient-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 122, 255, 0.02) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .enhanced-patient-card:hover::before {
            opacity: 1;
        }

        .enhanced-patient-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        /* View Toggle Buttons */
        .view-toggle-btn {
            transition: all 0.2s ease;
        }

        .view-toggle-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .view-toggle-btn.active {
            background: var(--primary) !important;
            color: var(--apple-white) !important;
            border-color: var(--primary) !important;
        }

        /* Patients Grid Layout */
        .patients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: var(--space-xl);
        }

        .patients-grid.list-view {
            grid-template-columns: 1fr;
        }

        .patients-grid.list-view .enhanced-patient-card {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }

        .patients-grid.list-view .patient-header {
            flex-direction: row;
            margin-bottom: 0;
            min-width: 200px;
        }

        .patients-grid.list-view .patient-details {
            grid-template-columns: repeat(3, 1fr);
            margin-bottom: 0;
            flex: 1;
        }

        .patients-grid.list-view .enhanced-patient-card > div:last-child {
            margin-left: auto;
            min-width: 200px;
        }

        /* Responsive Design for Patients Page */
        @media (max-width: 768px) {
            .patients-grid {
                grid-template-columns: 1fr;
            }
            
            .patients-grid.list-view .enhanced-patient-card {
                flex-direction: column;
                align-items: stretch;
            }
            
            .patients-grid.list-view .patient-header {
                flex-direction: column;
                text-align: center;
                min-width: auto;
            }
            
            .patients-grid.list-view .patient-details {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .patients-grid.list-view .enhanced-patient-card > div:last-child {
                margin-left: 0;
                min-width: auto;
            }
            
            .pagination-apple {
                flex-direction: column;
                gap: var(--space-md);
            }
            
            .pagination-apple .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Enhanced Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--apple-gray-2);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Enhanced Focus States */
        .form-input-apple:focus,
        .form-select-apple:focus,
        .form-textarea-apple:focus,
        .search-input-apple:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        /* Enhanced Hover States */
        .card-apple:hover,
        .patient-card-apple:hover,
        .testimonial-apple:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Enhanced Loading States */
        .btn-apple.loading {
            pointer-events: none;
        }

        .btn-apple.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Keyboard Navigation */
        .keyboard-navigation *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Apple-style Modal */
        .modal-apple {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-apple.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content-apple {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }

        .modal-apple.active .modal-content-apple {
            transform: scale(1);
        }

        .modal-header-apple {
            padding: var(--space-xl) var(--space-xl) 0;
            border-bottom: 1px solid var(--border-light);
            margin-bottom: var(--space-xl);
        }

        .modal-body-apple {
            padding: 0 var(--space-xl) var(--space-xl);
        }

        .modal-footer-apple {
            padding: var(--space-xl);
            border-top: 1px solid var(--border-light);
            display: flex;
            gap: var(--space-md);
            justify-content: flex-end;
        }
    </style>
</head>

<body>
    <!-- Apple-style Navigation -->
    <nav class="nav-apple" id="navbar">
        <div class="container-apple">
            <div style="display: flex; align-items: center; justify-content: space-between; height: 64px;">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="nav-brand">
                    DentalCare Pro
                </a>

                <!-- Desktop Navigation -->
                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                    <a href="{{ route('home') }}" class="nav-link-apple {{ request()->routeIs('home') ? 'active' : '' }}">
                        {{ __('common.home') }}
                    </a>
                    <a href="{{ route('about') }}" class="nav-link-apple {{ request()->routeIs('about') ? 'active' : '' }}">
                        {{ __('common.about') }}
                    </a>
                    <a href="{{ route('services') }}" class="nav-link-apple {{ request()->routeIs('services') ? 'active' : '' }}">
                        {{ __('common.services') }}
                    </a>
                    <a href="{{ route('patients') }}" class="nav-link-apple {{ request()->routeIs('patients') ? 'active' : '' }}">
                        {{ __('common.patients') }}
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link-apple {{ request()->routeIs('contact') ? 'active' : '' }}">
                        {{ __('common.contact') }}
                    </a>
                    
                    <!-- Language Switcher -->
                    <div class="language-switcher" style="margin-left: var(--space-md);">
                        <div class="dropdown" style="position: relative;">
                            <button class="btn-apple-outline" style="padding: var(--space-sm) var(--space-md); font-size: 0.875rem;" onclick="toggleDropdown(this)">
                                @if(app()->getLocale() == 'en')
                                    🇺🇸 EN
                                @elseif(app()->getLocale() == 'ps')
                                    🇦🇫 پښتو
                                @elseif(app()->getLocale() == 'fa')
                                    🇦🇫 دری
                                @endif
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: var(--space-xs);">
                                    <polyline points="6,9 12,15 18,9"></polyline>
                                </svg>
                            </button>
                            <div class="dropdown-menu" style="position: absolute; top: 100%; right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); min-width: 120px; z-index: 1000; display: none;">
                                <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item" style="display: block; padding: var(--space-sm) var(--space-md); color: var(--text-primary); text-decoration: none; border-radius: var(--radius-sm);">
                                    🇺🇸 English
                                </a>
                                <a href="{{ route('lang.switch', 'ps') }}" class="dropdown-item" style="display: block; padding: var(--space-sm) var(--space-md); color: var(--text-primary); text-decoration: none; border-radius: var(--radius-sm);">
                                    🇦🇫 پښتو
                                </a>
                                <a href="{{ route('lang.switch', 'fa') }}" class="dropdown-item" style="display: block; padding: var(--space-sm) var(--space-md); color: var(--text-primary); text-decoration: none; border-radius: var(--radius-sm);">
                                    🇦🇫 دری
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-left: var(--space-lg); padding-left: var(--space-lg); border-left: 1px solid var(--border-light);">
                        <a href="/admin" class="btn-apple">
                            {{ __('common.dashboard') }}
                        </a>
                        <a href="{{ route('financial.dashboard') }}" class="btn-apple-outline" style="margin-left: var(--space-sm);">
                            {{ __('common.reports') }}
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" style="display: none; background: none; border: none; padding: var(--space-sm); border-radius: var(--radius-md);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Apple-style Footer -->
    <footer class="footer-apple">
        <div class="container-apple">
            <div class="grid-apple grid-apple-4" style="margin-bottom: var(--space-2xl);">
                <!-- Brand -->
                <div>
                    <a href="{{ route('home') }}" class="nav-brand" style="margin-bottom: var(--space-lg); display: block;">
                        DentalCare Pro
                    </a>
                    <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: var(--space-lg); line-height: 1.6;">
                        {{ __('dental.providing_exceptional_care') }}
                    </p>
                    <div style="display: flex; gap: var(--space-md);">
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h6 style="color: var(--apple-white); font-weight: 600; margin-bottom: var(--space-lg);">{{ __('dental.quick_links') }}</h6>
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('home') }}" class="nav-link-apple">{{ __('common.home') }}</a>
                        <a href="{{ route('about') }}" class="nav-link-apple">{{ __('common.about') }}</a>
                        <a href="{{ route('services') }}" class="nav-link-apple">{{ __('common.services') }}</a>
                        <a href="{{ route('contact') }}" class="nav-link-apple">{{ __('common.contact') }}</a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h6 style="color: var(--apple-white); font-weight: 600; margin-bottom: var(--space-lg);">{{ __('common.services') }}</h6>
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
                        <a href="{{ route('patients') }}" class="nav-link-apple">{{ __('dental.patient_management') }}</a>
                        <a href="{{ route('financial.dashboard') }}" class="nav-link-apple">{{ __('dental.financial_reports') }}</a>
                        <a href="/admin" class="nav-link-apple">{{ __('common.dashboard') }}</a>
                        <a href="#appointment" class="nav-link-apple">{{ __('dental.book_appointment') }}</a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h6 style="color: var(--apple-white); font-weight: 600; margin-bottom: var(--space-lg);">{{ __('dental.contact_info') }}</h6>
                    <div style="display: flex; flex-direction: column; gap: var(--space-sm); color: rgba(255, 255, 255, 0.7);">
                        <div>📞 {{ __('dental.phone_main') }}</div>
                        <div>✉️ {{ __('dental.email_main') }}</div>
                        <div>📍 {{ __('dental.address_line') }}<br>{{ __('dental.city_country') }}</div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: var(--space-lg); text-align: center; color: rgba(255, 255, 255, 0.5);">
                <p>&copy; {{ date('Y') }} {{ __('dental.app_name') }}. {{ __('dental.all_rights_reserved') }}</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced form interactions
        document.querySelectorAll('.form-input-apple, .form-select-apple, .form-textarea-apple').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
                this.style.transform = 'translateY(-1px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                this.style.transform = 'translateY(0)';
            });

            // Add floating label effect
            input.addEventListener('input', function() {
                if (this.value) {
                    this.parentElement.classList.add('has-value');
                } else {
                    this.parentElement.classList.remove('has-value');
                }
            });
        });

        // Enhanced button interactions
        document.querySelectorAll('.btn-apple, .btn-apple-outline').forEach(button => {
            button.addEventListener('click', function(e) {
                // Add ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Enhanced card interactions
        document.querySelectorAll('.card-apple, .patient-card-apple, .testimonial-apple').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Enhanced search functionality
        const searchInput = document.querySelector('.search-input-apple');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchIcon = this.parentElement.querySelector('.search-icon');
                if (this.value) {
                    searchIcon.style.color = 'var(--primary)';
                } else {
                    searchIcon.style.color = 'var(--text-tertiary)';
                }
            });
        }

        // Enhanced form submission with loading states
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    
                    // Simulate form processing
                    setTimeout(() => {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                    }, 2000);
                }
            });
        });

        // Enhanced scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    
                    // Trigger counter animation for stats
                    if (entry.target.classList.contains('stat-apple')) {
                        animateCounters();
                    }
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.card-apple, .stat-apple, .testimonial-apple, .patient-card-apple').forEach(el => {
            observer.observe(el);
        });

        // Enhanced counter animation
        function animateCounters() {
            document.querySelectorAll('.stat-number').forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                if (target && !counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    const increment = target / 50;
                    let current = 0;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target + (counter.textContent.includes('+') ? '+' : '') + (counter.textContent.includes('%') ? '%' : '');
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current) + (counter.textContent.includes('+') ? '+' : '') + (counter.textContent.includes('%') ? '%' : '');
                        }
                    }, 30);
                }
            });
        }

        // Enhanced mobile menu
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                this.classList.toggle('active');
                // Mobile menu implementation would go here
                console.log('Mobile menu clicked');
            });
        }

        // Enhanced image lazy loading - Fixed version
        const images = document.querySelectorAll('img');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Only apply lazy loading if image has data-src attribute
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        
                        img.onload = () => {
                            img.style.opacity = '1';
                            img.style.transition = 'opacity 0.3s ease';
                        };
                    }
                    
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px', // Load images 50px before they come into view
            threshold: 0.1
        });

        images.forEach(img => {
            // Only observe images that have data-src attribute (lazy loaded)
            if (img.dataset.src) {
                img.style.opacity = '0.7'; // Set initial opacity for lazy loaded images
                imageObserver.observe(img);
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Language switcher dropdown
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            const isVisible = dropdown.style.display === 'block';
            
            // Close all dropdowns first
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
            
            // Toggle current dropdown
            if (!isVisible) {
                dropdown.style.display = 'block';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });

        // Enhanced error handling
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
        });

        // Enhanced performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
                }, 0);
            });
        }

        // Enhanced Patients Page Functionality
        function toggleView(viewType) {
            const grid = document.getElementById('patientsGrid');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            
            if (viewType === 'grid') {
                grid.classList.remove('list-view');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
                localStorage.setItem('patientsView', 'grid');
            } else {
                grid.classList.add('list-view');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
                localStorage.setItem('patientsView', 'list');
            }
        }

        // Restore view preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('patientsView');
            if (savedView === 'list') {
                toggleView('list');
            }
        });

        // Enhanced search functionality
        function clearSearch() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
        }

        function removeFilter(filterName) {
            const form = document.getElementById('searchForm');
            const input = form.querySelector(`[name="${filterName}"]`);
            if (input) {
                if (input.type === 'text') {
                    input.value = '';
                } else if (input.type === 'select-one') {
                    input.selectedIndex = 0;
                }
                form.submit();
            }
        }

        // Enhanced search with debouncing
        let searchTimeout;
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Auto-submit search after 500ms of no typing
                        if (this.value.length >= 3 || this.value.length === 0) {
                            this.form.submit();
                        }
                    }, 500);
                });
            }
        });

        // Enhanced patient card interactions
        document.addEventListener('DOMContentLoaded', function() {
            const patientCards = document.querySelectorAll('.enhanced-patient-card');
            patientCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Enhanced pagination interactions
        document.addEventListener('DOMContentLoaded', function() {
            const paginationLinks = document.querySelectorAll('.pagination-apple a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add loading state
                    const pagination = this.closest('.pagination-apple');
                    if (pagination) {
                        pagination.style.opacity = '0.7';
                        pagination.style.pointerEvents = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>