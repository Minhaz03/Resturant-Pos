<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favPos.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #8B0000; --primary-dark: #6B0000; --primary-light: #A50000;
            --secondary: #0A2647; --accent: #D4AF37;
            --bg: #F5F7FA;
        }
        * { box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: #2d3748; 
            margin: 0; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card { 
            border: none; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            background: #fff;
        }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .form-control, .form-select { border-radius: 8px; border-color: #e2e8f0; font-size: 0.875rem; padding: 0.6rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(139,0,0,0.1); }
        .form-label { font-weight: 500; font-size: 0.875rem; color: #4a5568; margin-bottom: 0.5rem; }
        .auth-logo {
            width: 60px;
            height: 60px;
            background: var(--accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .auth-logo i {
            font-size: 2rem;
            color: #fff;
        }
        .auth-title {
            text-align: center;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }
        .auth-subtitle {
            text-align: center;
            color: #718096;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <h4 class="auth-title">Grand RMS</h4>
            <p class="auth-subtitle">Restaurant Management System</p>
            
            {{ $slot }}
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
