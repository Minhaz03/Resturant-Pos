@extends('layouts.app')
@section('title', 'Billing & Subscription')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card p-4">
            <h5 class="fw-bold text-secondary mb-1">Current Subscription</h5>
            @if($activeSubscription)
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div>
                        <h3 class="fw-bold mb-0 text-success">{{ $activeSubscription->plan->name }} Plan</h3>
                        <p class="text-muted small mb-0">Valid until {{ $activeSubscription->ends_at ? $activeSubscription->ends_at->format('d M, Y') : 'Lifetime' }}</p>
                    </div>
                    <div>
                        <span class="badge bg-success py-2 px-3">Active</span>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> You do not have an active subscription. Please subscribe to a plan to continue using the platform.
                </div>
            @endif
        </div>
    </div>
</div>

<h5 class="fw-bold text-secondary mb-3 mt-2">Available Plans</h5>
<div class="row g-4 mb-5 justify-content-center">
    @foreach($plans as $plan)
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow transition-all {{ ($activeSubscription && $activeSubscription->plan_id == $plan->id) ? 'bg-primary-subtle' : 'bg-white' }}" style="{{ ($activeSubscription && $activeSubscription->plan_id == $plan->id) ? 'box-shadow: 0 0 0 2px var(--bs-primary);' : '' }}">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mb-4">
                    <span class="badge {{ ($activeSubscription && $activeSubscription->plan_id == $plan->id) ? 'bg-primary text-white' : 'bg-light text-primary border' }} px-3 py-2 rounded-pill fw-semibold mb-3 tracking-wide text-uppercase">{{ $plan->name }}</span>
                    <h2 class="display-6 fw-bolder mb-0 text-dark">৳{{ number_format($plan->price) }}</h2>
                    <p class="text-muted small mt-1 fw-medium">per {{ $plan->billing_cycle }}</p>
                </div>

                <div class="text-muted small mb-4 flex-grow-1 text-center px-2" style="line-height: 1.6;">
                    {{ $plan->description }}
                </div>
                
                <div class="mt-auto">
                    @if($activeSubscription && $activeSubscription->plan_id == $plan->id)
                        <button class="btn btn-primary w-100 rounded-pill fw-bold py-2 opacity-75" disabled>
                            <i class="bi bi-check-circle-fill me-2"></i>Current Plan
                        </button>
                    @else
                        <form action="{{ route('dashboard.billing.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold py-2 shadow-sm hover-btn-primary">
                                Purchase
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease-in-out;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,.08)!important;
}
.hover-btn-primary {
    transition: all 0.3s ease-in-out;
}
.hover-btn-primary:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}
</style>

<h5 class="fw-bold text-secondary mb-3">Billing History</h5>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Plan</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d M, Y') }}</td>
                        <td>{{ $item->plan->name }}</td>
                        <td>{{ $item->transaction_id ?? 'N/A' }}</td>
                        <td>৳{{ number_format($item->amount ?? $item->plan->price) }}</td>
                        <td>
                            @if($item->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($item->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->status == 'canceled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No billing history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
