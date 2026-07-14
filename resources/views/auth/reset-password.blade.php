<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <h3 class="auth-title">Reset Password</h3>
        <p class="auth-subtitle">Choose a strong new password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" readonly>
                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
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
            <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat new password">
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
</x-guest-layout>
