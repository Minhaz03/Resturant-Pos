@extends('layouts.app')
@section('title', 'Delivery Orders')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--secondary)">Delivery Orders</h4>
            <p class="text-muted small mb-0">Track and manage deliveries</p>
        </div>
        <a href="{{ route('delivery.riders') }}" class="btn btn-primary"><i class="bi bi-people"></i> Manage Riders</a>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total</p>
                    <h3 class="fw-bold" style="color:var(--secondary)">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Pending / Assigned</p>
                    <h3 class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">In Transit</p>
                    <h3 class="fw-bold text-primary">{{ $stats['in_transit'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted small mb-1">Delivered</p>
                    <h3 class="fw-bold text-success">{{ $stats['delivered'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search order..."
                    value="{{ request('search') }}" style="max-width:200px">
                <select name="status" class="form-select form-select-sm" style="max-width:160px">
                    <option value="">All Status</option>
                    @foreach (['pending', 'assigned', 'picked_up', 'on_way', 'delivered', 'failed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('delivery.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Rider</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $d)
                            <tr>
                                <td class="fw-semibold small">
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#trackingModal{{ $d->id }}"
                                        style="color:var(--secondary); text-decoration:none;">
                                        {{ $d->tracking_code ?? '—' }}
                                    </a>
                                </td>
                                <td>{{ $d->order?->order_number ?? '—' }}</td>
                                <td>
                                    <div>{{ $d->delivery_name ?? ($d->order?->customer?->name ?? 'Guest') }}</div>
                                    <div class="text-muted small">
                                        {{ $d->delivery_phone ?? ($d->order?->customer?->phone ?? '—') }}</div>
                                </td>
                                <td class="text-muted small"
                                    style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ $d->delivery_address ?? '—' }}</td>
                                <td>{!! $d->rider?->name ?? '<span class="text-muted small">Unassigned</span>' !!}</td>
                                <td>
                                    <span
                                        class="badge {{ match ($d->status ?? 'pending') {'pending' => 'bg-warning text-dark','assigned' => 'bg-info','picked_up' => 'bg-primary','on_way' => 'bg-primary','delivered' => 'bg-success','failed' => 'bg-danger','cancelled' => 'bg-secondary',default => 'bg-secondary'} }}">
                                        {{ ucfirst(str_replace('_', ' ', $d->status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $d->created_at->format('d M, h:i A') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-info py-0 px-2"
                                            data-bs-toggle="modal" data-bs-target="#trackingModal{{ $d->id }}"
                                            title="View Tracking Process">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if (!$d->rider_id && ($d->status == 'pending' || !$d->status))
                                            <button class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal"
                                                data-bs-target="#assignModal{{ $d->id }}" title="Assign Rider"><i
                                                    class="bi bi-person-plus"></i></button>
                                        @endif
                                        @if (in_array($d->status, ['assigned', 'picked_up', 'on_way']))
                                            <form method="POST" action="{{ route('delivery.update-status', $d) }}">@csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm"
                                                    style="width:120px" onchange="this.form.submit()">
                                                    <option value="">Update...</option>
                                                    @foreach (['picked_up' => 'Picked Up', 'on_way' => 'In Transit', 'delivered' => 'Delivered', 'failed' => 'Failed'] as $val => $lbl)
                                                        <option value="{{ $val }}"
                                                            {{ $d->status == $val ? 'selected' : '' }}>{{ $lbl }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                        @if (
                                            !(!$d->rider_id && ($d->status == 'pending' || !$d->status)) &&
                                                !in_array($d->status, ['assigned', 'picked_up', 'on_way']))
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <!-- Assign Modal -->
                            <div class="modal fade" id="assignModal{{ $d->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Assign Rider</h6><button type="button"
                                                class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('delivery.assign', $d) }}">@csrf
                                            <div class="modal-body">
                                                <select name="rider_id" class="form-select" required>
                                                    <option value="">Select Rider</option>
                                                    @foreach ($riders as $r)
                                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="modal-footer"><button type="button"
                                                    class="btn btn-sm btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button><button type="submit"
                                                    class="btn btn-sm btn-primary">Assign</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Tracking Modal -->
                            <div class="modal fade" id="trackingModal{{ $d->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg"
                                        style="border-radius: 16px; overflow: hidden;">
                                        <div class="modal-header border-bottom-0 bg-light pb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded p-2 me-3 d-flex align-items-center justify-content-center shadow-sm"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-box-seam fs-5"></i>
                                                </div>
                                                <div>
                                                    <h6 class="modal-title fw-bold mb-0">Transit Process</h6>
                                                    <small class="text-muted">Tracking ID: <span
                                                            class="fw-bold text-dark">{{ $d->tracking_code ?? 'N/A' }}</span></small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 pt-2">
                                            @php
                                                $progressWidth = match ($d->status) {
                                                    'pending' => '0%',
                                                    'assigned' => '25%',
                                                    'picked_up' => '50%',
                                                    'on_way' => '75%',
                                                    'delivered' => '100%',
                                                    default => '0%',
                                                };
                                                $step1 = [
                                                    'active' => $d->status == 'pending',
                                                    'completed' => in_array($d->status, [
                                                        'assigned',
                                                        'picked_up',
                                                        'on_way',
                                                        'delivered',
                                                    ]),
                                                ];
                                                $step2 = [
                                                    'active' => $d->status == 'assigned',
                                                    'completed' => in_array($d->status, [
                                                        'picked_up',
                                                        'on_way',
                                                        'delivered',
                                                    ]),
                                                ];
                                                $step3 = [
                                                    'active' => $d->status == 'picked_up',
                                                    'completed' => in_array($d->status, ['on_way', 'delivered']),
                                                ];
                                                $step4 = [
                                                    'active' => $d->status == 'on_way',
                                                    'completed' => in_array($d->status, ['delivered']),
                                                ];
                                                $step5 = ['active' => $d->status == 'delivered', 'completed' => false];
                                            @endphp

                                            <!-- Timeline -->
                                            <div class="tracking-timeline mx-4">
                                                <div class="tracking-progress-bg"></div>
                                                <div class="tracking-progress-bar" style="width: {{ $progressWidth }}">
                                                </div>

                                                <!-- Step 1 -->
                                                <div class="tracking-step {{ $step1['completed'] ? 'completed' : '' }} {{ $step1['active'] ? 'active' : '' }}"
                                                    style="left: 0%;">
                                                    <div class="tracking-icon"><i class="bi bi-cart3"></i></div>
                                                    <div class="tracking-label">Pending</div>
                                                    <div class="tracking-time">
                                                        {{ $d->created_at ? $d->created_at->format('d M H:i') : '—' }}
                                                    </div>
                                                </div>

                                                <!-- Step 2 -->
                                                <div class="tracking-step {{ $step2['completed'] ? 'completed' : '' }} {{ $step2['active'] ? 'active' : '' }}"
                                                    style="left: 25%;">
                                                    <div class="tracking-icon"><i class="bi bi-person-lines-fill"></i>
                                                    </div>
                                                    <div class="tracking-label">Assigned</div>
                                                    <div class="tracking-time">
                                                        {{ $d->assigned_at ? $d->assigned_at->format('d M H:i') : '—' }}
                                                    </div>
                                                </div>

                                                <!-- Step 3 -->
                                                <div class="tracking-step {{ $step3['completed'] ? 'completed' : '' }} {{ $step3['active'] ? 'active' : '' }}"
                                                    style="left: 50%;">
                                                    <div class="tracking-icon"><i class="bi bi-box-arrow-up"></i></div>
                                                    <div class="tracking-label">Picked Up</div>
                                                    <div class="tracking-time">
                                                        {{ $d->picked_up_at ? $d->picked_up_at->format('d M H:i') : '—' }}
                                                    </div>
                                                </div>

                                                <!-- Step 4 -->
                                                <div class="tracking-step {{ $step4['completed'] ? 'completed' : '' }} {{ $step4['active'] ? 'active' : '' }}"
                                                    style="left: 75%;">
                                                    <div class="tracking-icon"><i class="bi bi-truck"></i></div>
                                                    <div class="tracking-label">In Transit</div>
                                                    <div class="tracking-time"></div>
                                                </div>

                                                <!-- Step 5 -->
                                                <div class="tracking-step {{ $step5['completed'] ? 'completed' : '' }} {{ $step5['active'] ? 'active pulse-success' : '' }}"
                                                    style="left: 100%;">
                                                    <div class="tracking-icon"><i class="bi bi-check2-circle"></i></div>
                                                    <div class="tracking-label">Delivered</div>
                                                    <div class="tracking-time">
                                                        {{ $d->delivered_at ? $d->delivered_at->format('d M H:i') : '—' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details Section -->
                                            <div class="mt-5 pt-3">
                                                <h6 class="fw-bold mt-5 mb-3 d-flex align-items-center">
                                                    <i class="bi bi-info-circle text-primary me-2"></i> Delivery Details
                                                </h6>

                                                <div class="row g-3">
                                                    <div class="col-sm-6">
                                                        <div class="detail-card h-100 d-flex align-items-center">
                                                            <div class="detail-icon bg-primary bg-opacity-10 text-primary">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted d-block"
                                                                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Customer</span>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $d->delivery_name ?? ($d->order?->customer?->name ?? 'Guest') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="detail-card h-100 d-flex align-items-center">
                                                            <div class="detail-icon bg-info bg-opacity-10 text-info">
                                                                <i class="bi bi-telephone"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted d-block"
                                                                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Phone</span>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $d->delivery_phone ?? ($d->order?->customer?->phone ?? '—') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="detail-card d-flex align-items-center">
                                                            <div class="detail-icon bg-warning bg-opacity-10 text-warning">
                                                                <i class="bi bi-geo-alt"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted d-block"
                                                                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Destination</span>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $d->delivery_address ?? '—' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="detail-card h-100 d-flex align-items-center">
                                                            <div
                                                                class="detail-icon bg-secondary bg-opacity-10 text-secondary">
                                                                <i class="bi bi-bicycle"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted d-block"
                                                                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Rider</span>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $d->rider?->name ?? 'Unassigned' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="detail-card h-100 d-flex align-items-center">
                                                            <div
                                                                class="detail-icon {{ match ($d->status ?? 'pending') {'pending' => 'bg-warning text-dark bg-opacity-10','assigned' => 'bg-info text-info bg-opacity-10','picked_up' => 'bg-primary text-primary bg-opacity-10','on_way' => 'bg-primary text-primary bg-opacity-10','delivered' => 'bg-success text-success bg-opacity-10','failed' => 'bg-danger text-danger bg-opacity-10','cancelled' => 'bg-secondary text-secondary bg-opacity-10',default => 'bg-secondary text-secondary bg-opacity-10'} }}">
                                                                <i class="bi bi-activity"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted d-block"
                                                                    style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Status</span>
                                                                <span
                                                                    class="badge {{ match ($d->status ?? 'pending') {'pending' => 'bg-warning text-dark','assigned' => 'bg-info','picked_up' => 'bg-primary','on_way' => 'bg-primary','delivered' => 'bg-success','failed' => 'bg-danger','cancelled' => 'bg-secondary',default => 'bg-secondary'} }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $d->status ?? 'pending')) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No delivery orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($deliveries->hasPages())
            <div class="card-footer">{{ $deliveries->links() }}</div>
        @endif
    </div>

    <style>
        .tracking-timeline {
            position: relative;
            margin: 2rem 0 3.5rem 0;
            padding-top: 1rem;
        }

        .tracking-progress-bg {
            position: absolute;
            top: 25px;
            left: 0;
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 10px;
        }

        .tracking-progress-bar {
            position: absolute;
            top: 25px;
            left: 0;
            height: 6px;
            background: linear-gradient(90deg, #0d6efd, #198754);
            border-radius: 10px;
            transition: width 1s ease-in-out;
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.4);
        }

        .tracking-step {
            position: absolute;
            top: 0;
            transform: translateX(-50%);
            text-align: center;
            width: 100px;
            z-index: 2;
        }

        .tracking-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: #fff;
            border: 3px solid #e9ecef;
            color: #adb5bd;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .tracking-step.completed .tracking-icon {
            border-color: #198754;
            background: #198754;
            color: white;
            box-shadow: 0 0 15px rgba(25, 135, 84, 0.4);
        }

        .tracking-step.active .tracking-icon {
            border-color: #0d6efd;
            color: #0d6efd;
            box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.2);
            animation: pulse-ring 2s infinite;
        }

        .tracking-step.active.pulse-success .tracking-icon {
            border-color: #198754;
            color: #198754;
            box-shadow: 0 0 0 5px rgba(25, 135, 84, 0.2);
            animation: pulse-ring-success 2s infinite;
        }

        .tracking-step:hover .tracking-icon {
            transform: scale(1.1);
            cursor: default;
        }

        .tracking-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 2px;
            transition: color 0.3s ease;
        }

        .tracking-step.completed .tracking-label,
        .tracking-step.active .tracking-label {
            color: #212529;
        }

        .tracking-time {
            font-size: 0.7rem;
            color: #adb5bd;
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        @keyframes pulse-ring-success {
            0% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        .detail-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e9ecef;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .detail-icon {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }
    </style>
@endsection
