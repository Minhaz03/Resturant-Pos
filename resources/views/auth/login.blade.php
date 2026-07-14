<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <h3 class="auth-title">Welcome Back</h3>
        <p class="auth-subtitle">Sign in to manage your restaurant dashboard</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="auth-link" style="font-size: 0.8rem;" href="{{ route('password.request') }}">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter password">
                <button type="button" class="password-toggle-btn" id="passwordToggle" style="border: 1px solid #cbd5e1; border-left: none; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                    <i class="bi bi-eye" id="passwordIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check d-flex align-items-center">
            <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label ms-2" for="remember_me" style="font-size: 0.875rem; color: #475569; user-select: none;">
                {{ __('Remember this device') }}
            </label>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                {{ __('Sign In') }}
            </button>
        </div>

        <hr class="text-slate-300 my-4">

        <div class="text-center">
            <span class="text-muted small">Don't have a restaurant account?</span><br>
            <a class="auth-link small d-inline-block mt-1" href="{{ url('/') }}">
                <i class="bi bi-shop me-1"></i>{{ __('Register your restaurant') }}
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('passwordToggle');
        const icon = document.getElementById('passwordIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        }
    });
</script>
</x-guest-layout>
