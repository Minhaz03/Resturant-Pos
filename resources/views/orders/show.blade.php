@extends('layouts.app')
@section('title', 'Order Details')
@section('content')
    <div class="d-flex align-items-center gap-3 mb-4 no-print">
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $order->order_number }}</h4>
            <span class="badge"
                style="font-size:0.78rem;background:{{ match ($order->status) {'pending' => '#fef3c7','confirmed' => '#dbeafe','preparing' => '#ede9fe','ready' => '#d1fae5','served' => '#cffafe','completed' => '#dcfce7','cancelled' => '#fee2e2',default => '#f3f4f6'} }};color:{{ match ($order->status) {'pending' => '#92400e','confirmed' => '#1e40af','preparing' => '#5b21b6','ready' => '#065f46','served' => '#164e63','completed' => '#166534','cancelled' => '#991b1b',default => '#374151'} }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="ms-auto d-flex gap-2">
            @if (!$order->payment && $order->status !== 'cancelled')
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#settleModal"><i
                        class="bi bi-cash-coin me-1"></i>Settle Payment</button>
            @endif
            @if (!in_array($order->status, ['completed', 'cancelled']))
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Update Status</button>
                    <ul class="dropdown-menu border-0 shadow">
                        @foreach (['confirmed', 'preparing', 'ready', 'served', 'completed', 'cancelled'] as $s)
                            @if ($s != $order->status)
                                <li>
                                    <form method="POST" action="{{ route('orders.update-status', $order) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $s }}">
                                        <button type="submit"
                                            class="dropdown-item small {{ $s == 'cancelled' ? 'text-danger' : '' }}">{{ ucfirst($s) }}</button>
                                    </form>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
            <a href="{{ route('orders.print', $order) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i
                    class="bi bi-printer me-1"></i>Print Invoice</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Order Items</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Tax</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->item_name }}</td>
                                    <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-muted">৳{{ number_format($item->tax_amount, 2) }}</td>
                                    <td class="fw-semibold">৳{{ number_format($item->subtotal, 2) }}</td>
                                    <td>
                                        <span class="badge" style="font-size:0.72rem;background:{{ match($item->status){'pending'=>'#fef3c7','preparing'=>'#ede9fe','ready'=>'#d1fae5','served'=>'#cffafe','cancelled'=>'#fee2e2',default=>'#f3f4f6'} }};color:{{ match($item->status){'pending'=>'#92400e','preparing'=>'#5b21b6','ready'=>'#065f46','served'=>'#164e63','cancelled'=>'#991b1b',default=>'#374151'} }}">
                                            <i class="bi {{ match($item->status){'pending'=>'bi-hourglass-split','preparing'=>'bi-fire','ready'=>'bi-bell','served'=>'bi-person-check','cancelled'=>'bi-x-circle',default=>'bi-circle'} }} me-1"></i>{{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Order Summary</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 small"><span
                            class="text-muted">Subtotal:</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-2 small"><span
                            class="text-muted">Tax:</span><span>৳{{ number_format($order->tax_amount, 2) }}</span></div>
                    @if ($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 small"><span
                                class="text-muted">Discount:</span><span
                                class="text-danger">-৳{{ number_format($order->discount_amount, 2) }}</span></div>
                    @endif
                    @if ($order->coupon_discount > 0)
                        <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Coupon
                                ({{ $order->coupon_code }}):</span><span
                                class="text-danger">-৳{{ number_format($order->coupon_discount, 2) }}</span></div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold"><span>Total:</span><span
                            style="color:var(--primary)">৳{{ number_format($order->total_amount, 2) }}</span></div>
                    @if ($order->payment)
                        <hr>
                        <div class="d-flex justify-content-between small"><span class="text-muted">Paid via:</span><span
                                class="text-capitalize">{{ str_replace('_', ' ', $order->payment->method) }}</span></div>
                        <div class="d-flex justify-content-between small"><span class="text-muted">Status:</span><span
                                class="text-success">{{ ucfirst($order->payment->status) }}</span></div>
                    @else
                        <hr>
                        <div class="d-flex justify-content-between small"><span class="text-muted">Payment:</span><span
                                class="text-danger fw-bold">UNPAID</span></div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">Order Info</div>
                <div class="card-body small">
                    <div class="mb-2"><span class="text-muted">Type:</span>
                        {{ str_replace('_', ' ', ucfirst($order->type)) }}</div>
                    @if ($order->tables->count() > 0)
                        <div class="mb-2"><span class="text-muted">Table(s):</span>
                            {{ $order->tables->pluck('table_number')->implode(', ') }}</div>
                    @endif
                    @if ($order->customer)
                        <div class="mb-2"><span class="text-muted">Customer:</span> {{ $order->customer->name }}</div>
                    @endif
                    @if ($order->waiter)
                        <div class="mb-2"><span class="text-muted">Waiter:</span> {{ $order->waiter->name }}</div>
                    @endif
                    <div class="mb-2"><span class="text-muted">Created:</span>
                        {{ $order->created_at->format('d M Y, H:i') }}</div>
                    @if ($order->notes)
                        <div><span class="text-muted">Notes:</span> {{ $order->notes }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════ PAYMENT HISTORY ═══════════════════ --}}
    <div class="row g-3 mt-0">
        <div class="col-12">
            <div class="card" style="border-radius:14px;overflow:hidden;">
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                    style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid #e2e8f0;">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:32px;height:32px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;color:#1d4ed8;">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <span class="fw-bold" style="color:#1e293b;font-size:0.97rem;">Payment History</span>
                    </div>
                    @if($order->payment)
                        <span class="badge" style="background:#dcfce7;color:#166534;font-size:0.78rem;padding:6px 12px;border-radius:20px;">
                            <i class="bi bi-check-circle-fill me-1"></i>Paid
                        </span>
                    @else
                        <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:0.78rem;padding:6px 12px;border-radius:20px;">
                            <i class="bi bi-x-circle-fill me-1"></i>Unpaid
                        </span>
                    @endif
                </div>

                <div class="card-body p-0">
                    @if($order->payment)
                    {{-- ── PAID STATE ── --}}
                    <div class="p-4">
                        {{-- Top success banner --}}
                        <div class="d-flex align-items-center gap-3 p-3 mb-4 rounded-3"
                            style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;">
                            <div style="width:48px;height:48px;border-radius:50%;background:#16a34a;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;font-size:1.3rem;">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="color:#166534;font-size:1rem;">Payment Received</div>
                                <div class="small" style="color:#15803d;">
                                    {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y \a\t h:i A') : $order->payment->created_at->format('d M Y \a\t h:i A') }}
                                </div>
                            </div>
                            <div class="ms-auto text-end">
                                <div class="fw-bold" style="font-size:1.4rem;color:#166534;">৳{{ number_format($order->payment->amount, 2) }}</div>
                                <div class="small" style="color:#15803d;">Amount Paid</div>
                            </div>
                        </div>

                        {{-- Detail grid --}}
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Payment #</div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;">{{ $order->payment->payment_number }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Method</div>
                                    <div class="fw-semibold d-flex align-items-center gap-2" style="font-size:0.9rem;color:#0f172a;">
                                        @php
                                            $method = $order->payment->method;
                                            $methodIcon  = match($method) { 'cash' => 'bi-cash-coin', 'card' => 'bi-credit-card', 'mobile_banking' => 'bi-phone', 'split' => 'bi-layout-split', default => 'bi-wallet2' };
                                            $methodColor = match($method) { 'cash' => '#16a34a', 'card' => '#2563eb', 'mobile_banking' => '#7c3aed', 'split' => '#d97706', default => '#475569' };
                                        @endphp
                                        <i class="bi {{ $methodIcon }}" style="color:{{ $methodColor }};font-size:1rem;"></i>
                                        {{ ucwords(str_replace('_', ' ', $method)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Change Given</div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;">৳{{ number_format($order->payment->change_amount ?? 0, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Processed By</div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;">
                                        {{ $order->payment->processedBy?->name ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            @if($order->payment->transaction_id)
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Transaction ID</div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;word-break:break-all;">{{ $order->payment->transaction_id }}</div>
                                </div>
                            </div>
                            @endif

                            @if($order->payment->reference)
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Reference</div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;">{{ $order->payment->reference }}</div>
                                </div>
                            </div>
                            @endif

                            @if($order->payment->notes)
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
                                    <div class="small text-muted mb-1"><i class="bi bi-sticky me-1"></i>Notes</div>
                                    <div style="font-size:0.9rem;color:#0f172a;">{{ $order->payment->notes }}</div>
                                </div>
                            </div>
                            @endif

                            @if($order->payment->split_details)
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:#f0f9ff;border:1px solid #bae6fd;">
                                    <div class="small text-muted mb-2"><i class="bi bi-layout-split me-1"></i>Split Payment Details</div>
                                    <div class="row g-2">
                                        @foreach($order->payment->split_details as $split)
                                        <div class="col-sm-4">
                                            <div class="p-2 rounded-2 text-center" style="background:#fff;border:1px solid #e0f2fe;">
                                                <div class="small fw-semibold" style="color:#0369a1;">{{ ucwords(str_replace('_',' ',$split['method'] ?? '')) }}</div>
                                                <div class="fw-bold" style="color:#0f172a;">৳{{ number_format($split['amount'] ?? 0, 2) }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Amount breakdown footer --}}
                        <div class="mt-4 p-3 rounded-3 d-flex flex-wrap gap-3 align-items-center justify-content-end"
                            style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="text-center px-3">
                                <div class="small text-muted">Order Total</div>
                                <div class="fw-bold" style="color:#0f172a;">৳{{ number_format($order->total_amount, 2) }}</div>
                            </div>
                            <div style="color:#cbd5e1;font-size:1.2rem;">+</div>
                            <div class="text-center px-3">
                                <div class="small text-muted">Change</div>
                                <div class="fw-bold" style="color:#16a34a;">৳{{ number_format($order->payment->change_amount ?? 0, 2) }}</div>
                            </div>
                            <div style="color:#cbd5e1;font-size:1.2rem;">=</div>
                            <div class="text-center px-3">
                                <div class="small text-muted">Received</div>
                                <div class="fw-bold" style="font-size:1.15rem;color:#2563eb;">৳{{ number_format($order->total_amount + ($order->payment->change_amount ?? 0), 2) }}</div>
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- ── UNPAID STATE ── --}}
                    <div class="p-4">
                        <div class="d-flex align-items-start gap-3 p-3 mb-4 rounded-3"
                            style="background:linear-gradient(135deg,#fff7ed,#fef2f2);border:1.5px solid #fca5a5;">
                            <div style="width:48px;height:48px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#dc2626;font-size:1.2rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold" style="color:#b91c1c;font-size:1rem;">No Payment Recorded</div>
                                <div class="small" style="color:#6b7280;margin-top:2px;">This order has not been paid yet. The amount below is outstanding.</div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold" style="font-size:1.4rem;color:#dc2626;">৳{{ number_format($order->total_amount, 2) }}</div>
                                <div class="small" style="color:#b91c1c;">Due</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <div class="p-3 rounded-3 text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Subtotal</div>
                                    <div class="fw-semibold" style="color:#0f172a;">৳{{ number_format($order->subtotal, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 rounded-3 text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Tax</div>
                                    <div class="fw-semibold" style="color:#0f172a;">৳{{ number_format($order->tax_amount, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="p-3 rounded-3 text-center" style="background:#fef2f2;border:1px solid #fca5a5;">
                                    <div class="small text-muted mb-1">Total Due</div>
                                    <div class="fw-bold" style="color:#dc2626;font-size:1.05rem;">৳{{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        @if($order->status !== 'cancelled')
                        <div class="text-center">
                            <button class="btn btn-success px-4 py-2" data-bs-toggle="modal" data-bs-target="#settleModal"
                                style="border-radius:10px;font-weight:600;font-size:0.9rem;">
                                <i class="bi bi-cash-coin me-2"></i>Settle Payment Now
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Settle Payment Modal -->
    @if (!$order->payment && $order->status !== 'cancelled')
        <style>
            /* Payment Modal Styles */
            .payment-modal .method-btn {
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                padding: 12px;
                cursor: pointer;
                text-align: center;
                transition: all 0.2s;
            }

            .payment-modal .method-btn.selected {
                border-color: var(--primary);
                background: #fef2f2;
            }

            .payment-modal .method-btn:hover {
                border-color: var(--primary);
            }
        </style>
        <div class="modal fade" id="settleModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('orders.settle', $order) }}"
                    class="modal-content payment-modal border-0 shadow">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Pay to Proceed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)">
                                ৳{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-muted small">Total Amount Due</div>
                        </div>

                        <input type="hidden" name="payment_method" id="payment_method" value="cash">

                        <div class="row g-2 mb-3">
                            @foreach (['cash' => 'Cash', 'card' => 'Card', 'mobile_banking' => 'Mobile Banking'] as $val => $label)
                                <div class="col-4">
                                    <div class="method-btn {{ $val == 'cash' ? 'selected' : '' }}"
                                        onclick="selectMethodOrder('{{ $val }}')"
                                        data-method="{{ $val }}">
                                        <i class="bi {{ $val == 'cash' ? 'bi-cash-coin' : ($val == 'card' ? 'bi-credit-card' : 'bi-phone') }} fs-4 d-block mb-1"
                                            style="color:var(--primary)"></i>
                                        <div style="font-size:0.8rem;font-weight:600">{{ $label }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount Received</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">৳</span>
                                <input type="number" name="payment_amount" id="settleAmount"
                                    class="form-control border-start-0 ps-0" step="0.01"
                                    min="{{ $order->total_amount }}" value="{{ $order->total_amount }}" required
                                    oninput="calcSettleChange()"
                                    style="font-size: 1.2rem; font-weight: bold; color: var(--primary);">
                            </div>
                        </div>
                        <div class="bg-light rounded p-2 mb-3">
                            <div class="d-flex justify-content-between small align-items-center"><span>Change:</span><span
                                    id="settleChange" class="fw-bold text-success fs-5">৳0.00</span></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Confirm
                            Payment</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function selectMethodOrder(method) {
                document.getElementById('payment_method').value = method;
                document.querySelectorAll('.method-btn').forEach(btn => {
                    btn.classList.remove('selected');
                    if (btn.getAttribute('data-method') === method) {
                        btn.classList.add('selected');
                    }
                });
            }

            function calcSettleChange() {
                const total = {{ $order->total_amount }};
                const received = parseFloat(document.getElementById('settleAmount').value) || 0;
                const change = Math.max(0, received - total);
                document.getElementById('settleChange').textContent = '৳' + change.toFixed(2);
            }
        </script>
    @endif
@endsection
