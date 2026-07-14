<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <h3 class="auth-title">Forgot Password</h3>
        <p class="auth-subtitle">Get a secure link to reset your account password</p>
    </div>

    <div class="alert alert-success d-flex align-items-start gap-2 mb-4" role="alert" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
        <i class="bi bi-info-circle-fill mt-0.5 fs-5"></i>
        <div>
            {{ __('Forgot your password? No problem. Enter your registered email address below, and we will email you a password reset link.') }}
        </div>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <hr class="text-slate-300 my-4">

        <div class="text-center">
            <a class="auth-link small" href="{{ route('login') }}">
                <i class="bi bi-arrow-left me-1"></i>{{ __('Back to login') }}
            </a>
        </div>
    </form>
</div>
</x-guest-layout>
