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
<div class="row g-4 mb-5">
    @foreach($plans as $plan)
    <div class="col-md-4">
        <div class="card h-100 {{ ($activeSubscription && $activeSubscription->plan_id == $plan->id) ? 'border-primary' : '' }}" style="{{ ($activeSubscription && $activeSubscription->plan_id == $plan->id) ? 'box-shadow: 0 0 0 2px var(--primary);' : '' }}">
            <div class="card-body p-4 text-center d-flex flex-column">
                <h5 class="fw-bold text-secondary mb-3">{{ $plan->name }}</h5>
                <h2 class="fw-bold mb-0 text-primary">৳{{ number_format($plan->price) }}</h2>
                <p class="text-muted small mb-4">per {{ $plan->billing_cycle }}</p>

                <p class="text-muted small mb-4" style="min-height: 40px;">{{ $plan->description }}</p>
                
                <div class="mt-auto">
                    @if($activeSubscription && $activeSubscription->plan_id == $plan->id)
                        <button class="btn btn-outline-primary w-100" disabled>Current Plan</button>
                    @else
                        <form action="{{ route('dashboard.billing.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-primary w-100">Subscribe via SSLCommerz</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

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
