<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <h3 class="auth-title">Create Account</h3>
        <p class="auth-subtitle">Get started with a personal user account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="john@example.com">
                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password">
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                {{ __('Register') }}
            </button>
        </div>

        <hr class="text-slate-300 my-4">

        <div class="text-center">
            <span class="text-muted small">Already registered?</span><br>
            <a class="auth-link small d-inline-block mt-1" href="{{ route('login') }}">
                {{ __('Sign In to existing account') }}
            </a>
        </div>
    </form>
</div>
</x-guest-layout>
