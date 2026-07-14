<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Grand RMS') }} — Authentication</title>
    <link rel="icon" type="image/png" href="{{ asset('favPos.png') }}">

    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #8B0000; 
            --primary-dark: #6B0000; 
            --primary-light: #A50000;
            --secondary: #0A2647; 
            --accent: #D4AF37;
            --bg: #F8FAFC;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: #1e293b; 
            margin: 0; 
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-title {
            font-family: 'Outfit', sans-serif;
        }

        /* Split-screen Layout */
        .auth-container {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* Left Side: Brand Panel */
        .auth-brand-side {
            width: 40%;
            background: linear-gradient(135deg, var(--secondary) 0%, #031224 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 3rem;
            color: #fff;
            overflow: hidden;
            z-index: 1;
        }

        /* Ambient Glow Blobs */
        .auth-brand-side::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(139, 0, 0, 0.25) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .auth-brand-side::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.12) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-badge {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent) 0%, #B89020 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            transition: transform 0.3s ease;
        }

        .brand-logo-container:hover .brand-logo-badge {
            transform: rotate(-10deg) scale(1.05);
        }

        .brand-logo-badge i {
            font-size: 1.4rem;
            color: #fff;
        }

        .brand-logo-text {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .brand-logo-text span {
            color: var(--accent);
        }

        .brand-hero-content {
            margin: 4rem 0;
        }

        .brand-hero-title {
            font-weight: 800;
            font-size: 2.25rem;
            line-height: 1.25;
            margin-bottom: 1.25rem;
            background: linear-gradient(to right, #FFFFFF, #E2E8F0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-hero-subtitle {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        /* Glassmorphic feature items */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .feature-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(139, 0, 0, 0.15);
            border: 1px solid rgba(139, 0, 0, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }

        .feature-card-text {
            font-size: 0.9rem;
            font-weight: 500;
            color: #e2e8f0;
        }

        .brand-footer {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Right Side: Form Side */
        .auth-form-side {
            width: 60%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            position: relative;
        }

        /* Auth Card container */
        .auth-card-container {
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-logo-mobile {
            display: none;
            margin-bottom: 2rem;
            text-align: center;
        }

        .auth-card { 
            border: 1px solid #e2e8f0; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02); 
            background: #fff;
            padding: 2.5rem;
        }

        .auth-title {
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.5rem;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2.25rem;
        }

        /* Form Inputs & Controls Styling */
        .form-label { 
            font-weight: 600; 
            font-size: 0.8rem; 
            color: #334155; 
            margin-bottom: 0.45rem; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .form-control, .form-select { 
            border-radius: 10px; 
            border-color: #cbd5e1; 
            font-size: 0.9rem; 
            padding: 0.7rem 1.1rem; 
            color: #1e293b;
            transition: all 0.2s ease-in-out;
        }

        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .form-control:focus, .form-select:focus { 
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(139,0,0,0.12); 
            z-index: 3;
        }

        .btn-primary { 
            background: var(--primary); 
            border-color: var(--primary); 
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(139,0,0,0.2);
        }

        .btn-primary:hover, .btn-primary:focus { 
            background: var(--primary-dark); 
            border-color: var(--primary-dark); 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(139,0,0,0.3);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        /* Checkbox customization */
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(139,0,0,0.12);
        }

        /* Toggle password visibility styling */
        .password-toggle-btn {
            background: none;
            border: none;
            color: #64748b;
            padding: 0 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: color 0.15s ease;
        }

        .password-toggle-btn:hover {
            color: var(--primary);
        }

        /* Links styling */
        a.auth-link {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        a.auth-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Alerts Custom Styling */
        .alert {
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 0.85rem 1rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger, .invalid-feedback {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .invalid-feedback {
            color: #dc2626;
            margin-top: 0.35rem;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .auth-brand-side {
                display: none;
            }
            .auth-form-side {
                width: 100%;
                background-color: var(--bg);
            }
            .auth-logo-mobile {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Side: Brand Panel -->
        <div class="auth-brand-side">
            <!-- Brand Logo -->
            <a href="/" class="brand-logo-container">
                <div class="brand-logo-badge">
                    <i class="bi bi-cup-hot-fill"></i>
                </div>
                <div class="brand-logo-text">Grand<span>RMS</span></div>
            </a>

            <!-- Hero Section -->
            <div class="brand-hero-content">
                <h2 class="brand-hero-title">Empower Your Restaurant's Performance.</h2>
                <p class="brand-hero-subtitle">Streamline your entire operation—from high-speed tableside POS and real-time kitchen displays to smart inventory control and delivery tracking.</p>

                <!-- Features list -->
                <div class="feature-list">
                    <div class="feature-card">
                        <div class="feature-card-icon"><i class="bi bi-lightning-charge"></i></div>
                        <div class="feature-card-text">Lightning-fast cloud POS system</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon"><i class="bi bi-tv"></i></div>
                        <div class="feature-card-text">Real-time Kitchen Display (KDS)</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="feature-card-text">Rich analytics, custom tax, & sales reports</div>
                    </div>
                </div>
            </div>

            <!-- Footer copyright -->
            <div class="brand-footer">
                &copy; 2026 Grand RMS. All rights reserved. Built for exceptional dining experiences.
            </div>
        </div>

        <!-- Right Side: Form Side -->
        <div class="auth-form-side">
            <div class="auth-card-container">
                
                <!-- Mobile Logo -->
                <div class="auth-logo-mobile">
                    <div class="d-inline-flex align-items-center gap-2 mb-3">
                        <div class="brand-logo-badge" style="width:40px; height:40px;">
                            <i class="bi bi-cup-hot-fill" style="font-size:1.2rem"></i>
                        </div>
                        <div class="brand-logo-text" style="color:var(--secondary); font-size:1.25rem">Grand<span style="color:var(--primary)">RMS</span></div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="auth-card">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
