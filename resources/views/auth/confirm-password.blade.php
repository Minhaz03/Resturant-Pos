<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <h3 class="auth-title">Confirm Password</h3>
        <p class="auth-subtitle">Verify your identity before proceeding</p>
    </div>

    <div class="text-muted mb-4 small">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</div>
</x-guest-layout>
