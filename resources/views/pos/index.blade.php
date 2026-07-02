@extends('layouts.app')
@section('title', 'POS System')
@push('styles')
<style>
    .pos-layout { display:flex; gap:16px; height:calc(100vh - 120px); overflow:hidden; }
    .pos-menu { flex:1; overflow-y:auto; }
    .pos-cart { width:360px; flex-shrink:0; display:flex; flex-direction:column; }
    .menu-cat-tab { display:flex; gap:6px; flex-wrap:nowrap; overflow-x:auto; padding-bottom:6px; }
    .menu-cat-tab::-webkit-scrollbar { height:3px; }
    .cat-tab { white-space:nowrap; border:none; background:#f1f5f9; color:#64748b; padding:5px 14px; border-radius:20px; cursor:pointer; font-size:0.82rem; font-weight:500; }
    .cat-tab.active { background:var(--primary); color:#fff; }
    .pos-item { background:#fff; border-radius:10px; padding:12px; cursor:pointer; transition:all 0.15s; border:2px solid transparent; box-shadow:0 1px 4px rgba(0,0,0,0.05); }
    .pos-item:hover { border-color:var(--primary); transform:translateY(-2px); box-shadow:0 4px 12px rgba(139,0,0,0.15); }
    .pos-item img { width:100%; height:70px; object-fit:cover; border-radius:7px; margin-bottom:8px; }
    .pos-item .item-img-placeholder { width:100%; height:70px; background:#f8fafc; border-radius:7px; display:flex; align-items:center; justify-content:center; margin-bottom:8px; }
    .pos-item .item-name { font-size:0.82rem; font-weight:600; line-height:1.3; }
    .pos-item .item-price { font-size:0.88rem; font-weight:700; color:var(--primary); margin-top:4px; }
    .cart-area { flex:1; overflow-y:auto; padding:10px; }
    .cart-item-row { display:flex; align-items:center; gap:8px; padding:8px 0; border-bottom:1px solid #f1f5f9; }
    .cart-item-row .item-info { flex:1; }
    .qty-btn { width:26px; height:26px; border-radius:6px; border:1px solid #e2e8f0; background:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:0.9rem; }
    .qty-btn:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
    .cart-footer { padding:10px; border-top:1px solid #f1f5f9; }
    .pay-btn { background:var(--primary); color:#fff; border:none; border-radius:10px; padding:12px; font-weight:700; font-size:1rem; cursor:pointer; transition:all 0.2s; }
    .pay-btn:hover { background:var(--primary-dark); }
    .payment-modal .method-btn { border:2px solid #e2e8f0; border-radius:10px; padding:12px; cursor:pointer; text-align:center; transition:all 0.2s; }
    .payment-modal .method-btn.selected { border-color:var(--primary); background:#fef2f2; }
    .payment-modal .method-btn:hover { border-color:var(--primary); }

    @media print {
        body { background: white !important; color: black !important; padding: 0 !important; margin: 0 !important; }
        #main, #sidebar, .modal, .modal-backdrop, .pos-layout, #pageLoader, #topbar { display: none !important; }
        #receipt-print { display: block !important; width: 80mm; padding: 5mm; margin: 0 auto; font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.4; color: #000; }
        #receipt-print th, #receipt-print td { font-size: 11px; }
    }
    #receipt-print { display: none; }
</style>
@endpush
@section('content')
<div class="pos-layout">
    <!-- Menu Side -->
    <div class="pos-menu">
        <div class="mb-2 d-flex gap-2 align-items-center">
            <input type="text" id="posSearch" class="form-control form-control-sm" placeholder="Search or scan barcode...">
            <select id="posSort" class="form-select form-select-sm" style="max-width:160px" onchange="sortItems()">
                <option value="default">Sort: Default</option>
                <option value="name_asc">Name A→Z</option>
                <option value="name_desc">Name Z→A</option>
                <option value="price_asc">Price Low→High</option>
                <option value="price_desc">Price High→Low</option>
            </select>
        </div>
        <div class="menu-cat-tab mb-3">
            <button class="cat-tab active" data-cat="all">All</button>
            @foreach($categories as $cat)
            @if($cat->activeMenuItems->count())
            <button class="cat-tab" data-cat="{{ $cat->id }}">{{ $cat->name }}</button>
            @endif
            @endforeach
        </div>
        <div class="row g-2" id="posMenuGrid">
            @foreach($categories as $cat)
            @foreach($cat->activeMenuItems as $item)
            <div class="col-xl-2 col-lg-3 col-md-4 col-6 pos-item-wrap" data-cat="{{ $cat->id }}" data-name="{{ strtolower($item->name) }}" data-sku="{{ $item->sku }}" data-barcode="{{ $item->barcode }}" data-price="{{ $item->effective_price }}">
                <div class="pos-item" onclick="addToCart({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->effective_price }})">
                    @if($item->hasMedia('image') || $item->image)
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    @else
                    <div class="item-img-placeholder"><i class="bi bi-image text-muted fs-4"></i></div>
                    @endif
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-price">৳{{ number_format($item->effective_price,2) }}</div>
                    @if($item->is_featured)<span class="badge bg-warning text-dark" style="font-size:0.65rem">Featured</span>@endif
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Cart Side -->
    <div class="pos-cart card">
        <div class="card-header py-2">
            <div class="row g-2">
                <div class="col-7">
                    <select id="cartCustomer" class="form-select form-select-sm">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}" data-points="{{ $c->loyalty_points }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-5">
                    <select id="cartTable" class="form-select form-select-sm">
                        <option value="">No Table</option>
                        @foreach($tables as $t)
                        <option value="{{ $t->id }}">{{ $t->table_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="cart-area" id="cartArea">
            <div id="cartEmpty" class="text-center py-5 text-muted small">
                <i class="bi bi-cart3 fs-2 d-block mb-2"></i>Cart is empty
            </div>
        </div>

        <div class="cart-footer">
            <!-- Coupon -->
            <div class="input-group input-group-sm mb-2">
                <input type="text" id="couponCode" class="form-control" placeholder="Coupon code">
                <button class="btn btn-outline-secondary" onclick="applyCoupon()">Apply</button>
            </div>
            <div id="couponInfo" class="text-success small mb-2 d-none"></div>

            <!-- Order Type -->
            <div class="btn-group btn-group-sm w-100 mb-2">
                <input type="radio" class="btn-check" name="orderType" id="ot1" value="dine_in" checked>
                <label class="btn btn-outline-secondary" for="ot1">Dine In</label>
                <input type="radio" class="btn-check" name="orderType" id="ot2" value="takeaway">
                <label class="btn btn-outline-secondary" for="ot2">Takeaway</label>
                <input type="radio" class="btn-check" name="orderType" id="ot3" value="delivery">
                <label class="btn btn-outline-secondary" for="ot3">Delivery</label>
            </div>

            <!-- Totals -->
            <div class="bg-light rounded p-2 mb-2">
                <div class="d-flex justify-content-between small mb-1"><span class="text-muted">Subtotal:</span><span id="cartSubtotal">৳0.00</span></div>
                <div class="d-flex justify-content-between small mb-1"><span class="text-muted">Tax (5%):</span><span id="cartTax">৳0.00</span></div>
                <div class="d-flex justify-content-between small mb-1 text-danger" id="discountRow" style="display:none!important"><span>Discount:</span><span id="cartDiscount">-৳0.00</span></div>
                <div class="d-flex justify-content-between fw-bold border-top pt-1 mt-1"><span>Total:</span><span id="cartTotal" style="color:var(--primary)">৳0.00</span></div>
            </div>

            <div class="d-flex gap-2 mb-2">
                <button class="btn btn-outline-danger btn-sm flex-fill" onclick="clearCart()"><i class="bi bi-trash me-1"></i>Clear</button>
            </div>
            <button class="pay-btn w-100" onclick="openPayment()">
                <i class="bi bi-cash-coin me-2"></i>Process Payment
            </button>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content payment-modal border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Process Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="fw-bold" style="font-size:1.5rem;color:var(--primary)" id="payAmount">৳0.00</div>
                    <div class="text-muted small">Total Amount Due</div>
                </div>
                <div class="row g-2 mb-3">
                    @foreach(['cash'=>'Cash','card'=>'Card','mobile_banking'=>'Mobile Banking'] as $val => $label)
                    <div class="col-4">
                        <div class="method-btn {{ $val=='cash'?'selected':'' }}" onclick="selectMethod('{{ $val }}')" data-method="{{ $val }}">
                            <i class="bi {{ $val=='cash'?'bi-cash-coin':($val=='card'?'bi-credit-card':'bi-phone') }} fs-4 d-block mb-1" style="color:var(--primary)"></i>
                            <div style="font-size:0.8rem;font-weight:600">{{ $label }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Amount Received</label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number" id="receivedAmount" class="form-control" step="0.01" placeholder="0.00" oninput="calcChange()">
                    </div>
                </div>
                <div class="bg-light rounded p-2" id="changeInfo">
                    <div class="d-flex justify-content-between small"><span>Change:</span><span id="changeAmount" class="fw-bold text-success">৳0.00</span></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmPayment()"><i class="bi bi-check2-circle me-1"></i>Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center">
            <div class="modal-body py-4">
                <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                <h5 class="fw-bold">Payment Successful!</h5>
                <div class="text-muted small mb-3" id="receiptInfo"></div>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-secondary btn-sm" onclick="printReceipt()"><i class="bi bi-printer me-1"></i>Print</button>
                    <button class="btn btn-primary btn-sm" onclick="newOrder()">New Order</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Hidden Thermal Receipt Block --}}
<div id="receipt-print">
    <div style="text-align:center;margin-bottom:8px">
        <strong style="font-size:14px" id="rp-name">{{ $setting->name ?? config('app.name') }}</strong><br>
        <small id="rp-address">{{ $setting->address ?? '' }}</small><br>
        <small id="rp-phone">{{ $setting->phone ?? '' }}</small>
    </div>
    <hr style="border-top:1px dashed #000;margin:6px 0">
    <div style="display:flex;justify-content:space-between;margin-bottom:4px">
        <small>Order:</small><small id="rp-order-no">—</small>
    </div>
    <div style="display:flex;justify-content:space-between;margin-bottom:4px">
        <small>Date:</small><small id="rp-date">—</small>
    </div>
    <div style="display:flex;justify-content:space-between;margin-bottom:4px">
        <small>Cashier:</small><small>{{ auth()->user()->name }}</small>
    </div>
    <hr style="border-top:1px dashed #000;margin:6px 0">
    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:left">Item</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:right">Amt</th>
            </tr>
        </thead>
        <tbody id="rp-items"></tbody>
    </table>
    <hr style="border-top:1px dashed #000;margin:6px 0">
    <div style="display:flex;justify-content:space-between"><span>Subtotal:</span><span id="rp-subtotal">৳0.00</span></div>
    <div style="display:flex;justify-content:space-between"><span>Tax (5%):</span><span id="rp-tax">৳0.00</span></div>
    <div id="rp-disc-row" style="display:flex;justify-content:space-between;display:none"><span>Discount:</span><span id="rp-disc">-৳0.00</span></div>
    <div style="display:flex;justify-content:space-between;font-weight:bold;border-top:1px dashed #000;margin-top:6px;padding-top:4px"><span>TOTAL:</span><span id="rp-total">৳0.00</span></div>
    <div style="display:flex;justify-content:space-between"><span>Change:</span><span id="rp-change">৳0.00</span></div>
    <hr style="border-top:1px dashed #000;margin:6px 0">
    <div style="text-align:center;margin-top:8px">
        <small id="rp-footer">{{ $setting->receipt_footer ?? 'Thank you for your visit!' }}</small>
    </div>
</div>

@push('scripts')
<script>
let cart = {};
let couponData = null;
let selectedMethod = 'cash';
let lastOrderData = null;

function addToCart(id, name, price) {
    if (cart[id]) cart[id].qty++;
    else cart[id] = { id, name, price, qty: 1 };
    renderCart();
}

function removeFromCart(id) { delete cart[id]; renderCart(); }

function updateQty(id, delta) {
    cart[id].qty = Math.max(0, cart[id].qty + delta);
    if (cart[id].qty === 0) delete cart[id];
    renderCart();
}

function clearCart() { cart = {}; couponData = null; document.getElementById('couponCode').value=''; document.getElementById('couponInfo').classList.add('d-none'); renderCart(); }

function getSubtotal() { return Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0); }

function renderCart() {
    const area = document.getElementById('cartArea');
    const empty = document.getElementById('cartEmpty');
    const items = Object.values(cart);
    if (!items.length) { area.innerHTML = ''; area.appendChild(empty); updateTotals(); return; }
    area.innerHTML = items.map(item => `
        <div class="cart-item-row">
            <div class="item-info">
                <div class="fw-semibold" style="font-size:0.83rem">${item.name}</div>
                <div style="color:var(--primary);font-size:0.82rem">৳${(item.price*item.qty).toFixed(2)}</div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <div class="qty-btn" onclick="updateQty(${item.id},-1)">-</div>
                <span class="px-1 fw-bold">${item.qty}</span>
                <div class="qty-btn" onclick="updateQty(${item.id},1)">+</div>
                <div class="qty-btn text-danger" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></div>
            </div>
        </div>`).join('');
    updateTotals();
}

function updateTotals() {
    const sub = getSubtotal();
    const tax = sub * 0.05;
    let disc = 0;
    if (couponData) { disc = couponData.type === 'percentage' ? Math.min(sub * couponData.value / 100, couponData.max_discount || 9999) : couponData.value; }
    const total = Math.max(0, sub + tax - disc);
    document.getElementById('cartSubtotal').textContent = '৳' + sub.toFixed(2);
    document.getElementById('cartTax').textContent = '৳' + tax.toFixed(2);
    document.getElementById('cartTotal').textContent = '৳' + total.toFixed(2);
    if (disc > 0) { document.getElementById('cartDiscount').textContent = '-৳' + disc.toFixed(2); document.getElementById('discountRow').style.removeProperty('display'); }
    else document.getElementById('discountRow').style.setProperty('display','none','important');
    return total;
}

function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    if (!code) return;
    fetch('{{ route("pos.validate-coupon") }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({code}) })
        .then(r=>r.json()).then(d=>{
            if (d.valid) { couponData = d; document.getElementById('couponInfo').textContent = '✓ '+d.name+' applied!'; document.getElementById('couponInfo').classList.remove('d-none'); updateTotals(); }
            else { document.getElementById('couponInfo').textContent = d.message; document.getElementById('couponInfo').classList.remove('d-none','text-success'); document.getElementById('couponInfo').classList.add('text-danger'); }
        });
}

function selectMethod(m) { selectedMethod = m; document.querySelectorAll('.method-btn').forEach(b => b.classList.toggle('selected', b.dataset.method === m)); }

function openPayment() {
    if (!Object.keys(cart).length) { alert('Cart is empty!'); return; }
    const total = updateTotals();
    document.getElementById('payAmount').textContent = '৳' + total.toFixed(2);
    document.getElementById('receivedAmount').value = total.toFixed(2);
    calcChange();
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

function calcChange() {
    const total = parseFloat(document.getElementById('cartTotal').textContent.replace('৳',''));
    const received = parseFloat(document.getElementById('receivedAmount').value) || 0;
    document.getElementById('changeAmount').textContent = '৳' + Math.max(0, received - total).toFixed(2);
}

function confirmPayment() {
    const items = Object.values(cart).map(i => ({ menu_item_id: i.id, quantity: i.qty }));
    const total = parseFloat(document.getElementById('cartTotal').textContent.replace('৳',''));
    const received = parseFloat(document.getElementById('receivedAmount').value) || total;
    const payload = {
        items, payment_method: selectedMethod, payment_amount: received,
        order_type: document.querySelector('input[name=orderType]:checked').value,
        table_id: document.getElementById('cartTable').value || null,
        customer_id: document.getElementById('cartCustomer').value || null,
        coupon_code: couponData ? document.getElementById('couponCode').value : null,
    };
    fetch('{{ route("pos.process") }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify(payload) })
        .then(r=>r.json()).then(d=>{
            if (d.success) {
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                lastOrderData = d;
                document.getElementById('receiptInfo').innerHTML = `Order: <strong>${d.order_number}</strong><br>Total: ৳${d.total.toFixed(2)}<br>Change: ৳${d.change.toFixed(2)}`;
                new bootstrap.Modal(document.getElementById('receiptModal')).show();
            } else { alert('Error: ' + d.message); }
        }).catch(e => alert('Error processing payment'));
}

function printReceipt() {
    if (!lastOrderData) return;
    const items = Object.values(cart);
    const sub = getSubtotal();
    const tax = sub * 0.05;
    let disc = 0;
    if (couponData) { disc = couponData.type === 'percentage' ? Math.min(sub * couponData.value / 100, couponData.max_discount || 9999) : couponData.value; }
    const total = Math.max(0, sub + tax - disc);
    const change = lastOrderData.change ?? 0;
    // Populate receipt
    document.getElementById('rp-order-no').textContent = lastOrderData.order_number;
    document.getElementById('rp-date').textContent = new Date().toLocaleString();
    document.getElementById('rp-subtotal').textContent = '৳' + sub.toFixed(2);
    document.getElementById('rp-tax').textContent = '৳' + tax.toFixed(2);
    document.getElementById('rp-total').textContent = '৳' + total.toFixed(2);
    document.getElementById('rp-change').textContent = '৳' + change.toFixed(2);
    if (disc > 0) {
        document.getElementById('rp-disc').textContent = '-৳' + disc.toFixed(2);
        document.getElementById('rp-disc-row').style.display = 'flex';
    }
    const tbody = document.getElementById('rp-items');
    tbody.innerHTML = items.map(i => `<tr><td>${i.name}</td><td style="text-align:center">${i.qty}</td><td style="text-align:right">৳${(i.price*i.qty).toFixed(2)}</td></tr>`).join('');
    window.print();
}
function newOrder() { clearCart(); couponData = null; lastOrderData = null; bootstrap.Modal.getInstance(document.getElementById('receiptModal')).hide(); }

// Sort
function sortItems() {
    const val = document.getElementById('posSort').value;
    const grid = document.getElementById('posMenuGrid');
    const items = Array.from(grid.querySelectorAll('.pos-item-wrap'));
    items.sort((a, b) => {
        if (val === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
        if (val === 'name_desc') return b.dataset.name.localeCompare(a.dataset.name);
        if (val === 'price_asc') return parseFloat(a.dataset.price||0) - parseFloat(b.dataset.price||0);
        if (val === 'price_desc') return parseFloat(b.dataset.price||0) - parseFloat(a.dataset.price||0);
        return 0;
    });
    items.forEach(el => grid.appendChild(el));
}

// Category filter
document.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.pos-item-wrap').forEach(el => {
            el.style.display = (cat === 'all' || el.dataset.cat === cat) ? '' : 'none';
        });
    });
});

// Search
document.getElementById('posSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.pos-item-wrap').forEach(el => {
        const match = el.dataset.name.includes(q) || (el.dataset.sku||'').toLowerCase().includes(q) || (el.dataset.barcode||'').toLowerCase() === q;
        el.style.display = match ? '' : 'none';
    });
});
</script>
@endpush
