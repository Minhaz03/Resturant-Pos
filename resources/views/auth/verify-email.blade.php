<x-guest-layout>
<div>
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-light text-primary rounded-circle mb-3" style="width: 60px; height: 60px; font-size: 1.8rem; background-color: rgba(139,0,0,0.06) !important;">
            <i class="bi bi-envelope-open-fill text-danger"></i>
        </div>
        <h3 class="auth-title">Verify Email</h3>
        <p class="auth-subtitle">Verify your email address to access your dashboard</p>
    </div>

    <div class="text-muted mb-4 small text-center">
        {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.") }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4 text-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ __('A new verification link has been sent to the email address you provided.') }}
        </div>
    @endif

    <div class="d-flex flex-column gap-2 mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2.5 fw-semibold">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="btn btn-link auth-link small">
                <i class="bi bi-box-arrow-right me-1"></i>{{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
</x-guest-layout>
