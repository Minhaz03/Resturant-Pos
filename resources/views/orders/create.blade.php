@extends('layouts.app')
@section('title','New Order')
@push('styles')
<style>
.menu-item-card { cursor:pointer; transition:all 0.2s; border:2px solid transparent; }
.menu-item-card:hover { border-color:var(--primary); transform:translateY(-2px); }
.cart-item { border-bottom: 1px solid #f1f5f9; }
</style>
@endpush
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">New Order</h4>
</div>
<div class="row g-3">
    <!-- Menu Panel -->
    <div class="col-lg-8">
        <div class="card mb-3"><div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-6"><input type="text" id="menuSearch" class="form-control form-control-sm" placeholder="Search menu items..."></div>
                <div class="col-md-6">
                    <div class="d-flex gap-1 flex-wrap" id="categoryTabs">
                        <button class="btn btn-sm btn-primary cat-btn active" data-cat="all">All</button>
                        @foreach($categories as $cat)
                        <button class="btn btn-sm btn-outline-secondary cat-btn" data-cat="{{ $cat->id }}">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div></div>
        <div class="row g-2" id="menuGrid">
            @foreach($categories as $cat)
            @foreach($cat->activeMenuItems as $item)
            <div class="col-md-4 col-6 menu-item-wrap" data-cat="{{ $cat->id }}" data-name="{{ strtolower($item->name) }}">
                <div class="card menu-item-card" onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->effective_price }})">
                    <div class="card-body p-3 text-center">
                        <div class="fw-semibold" style="font-size:0.85rem">{{ $item->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem">{{ $cat->name }}</div>
                        <div class="fw-bold mt-1" style="color:var(--primary)">৳{{ number_format($item->effective_price,2) }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

    <!-- Order Panel -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top:80px">
            <div class="card-header">
                <div class="row g-2">
                    <div class="col-6">
                        <select name="table_id" id="tableId" class="form-select form-select-sm">
                            <option value="">Select Table</option>
                            @foreach($tables as $t)
                            <option value="{{ $t->id }}">{{ $t->table_number }} ({{ $t->capacity }}p)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select id="orderType" class="form-select form-select-sm">
                            <option value="dine_in">Dine In</option>
                            <option value="takeaway">Takeaway</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="cartItems" style="max-height:300px;overflow-y:auto">
                    <div id="emptyCart" class="text-center py-4 text-muted small">
                        <i class="bi bi-cart3 fs-3 d-block mb-2"></i>No items yet.<br>Click menu items to add.
                    </div>
                </div>
                <div class="p-3 border-top">
                    <div class="d-flex justify-content-between small mb-1"><span>Subtotal:</span><span id="subtotalDisp">৳0.00</span></div>
                    <div class="d-flex justify-content-between small mb-1 text-muted"><span>Tax (5%):</span><span id="taxDisp">৳0.00</span></div>
                    <div class="d-flex justify-content-between fw-bold"><span>Total:</span><span id="totalDisp" style="color:var(--primary)">৳0.00</span></div>
                </div>
            </div>
            <div class="card-footer">
                <textarea id="orderNotes" class="form-control form-control-sm mb-2" rows="2" placeholder="Order notes..."></textarea>
                <button class="btn btn-primary w-100" onclick="submitOrder()"><i class="bi bi-check2-circle me-1"></i>Place Order</button>
            </div>
        </div>
    </div>
</div>

<form id="orderForm" method="POST" action="{{ route('orders.store') }}">@csrf</form>
@endsection
@push('scripts')
<script>
let cart = {};
function addToCart(id, name, price) {
    if (cart[id]) cart[id].qty++;
    else cart[id] = { id, name, price, qty: 1 };
    renderCart();
}
function removeFromCart(id) { delete cart[id]; renderCart(); }
function updateQty(id, delta) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}
function renderCart() {
    const el = document.getElementById('cartItems');
    const empty = document.getElementById('emptyCart');
    const items = Object.values(cart);
    if (!items.length) { el.innerHTML = ''; el.appendChild(empty); return; }
    let html = '';
    let subtotal = 0;
    items.forEach(item => {
        subtotal += item.price * item.qty;
        html += `<div class="cart-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold" style="font-size:0.85rem">${item.name}</div>
                <button onclick="removeFromCart(${item.id})" class="btn btn-sm text-danger p-0"><i class="bi bi-x"></i></button>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <div class="d-flex align-items-center gap-1">
                    <button onclick="updateQty(${item.id},-1)" class="btn btn-sm btn-outline-secondary py-0 px-2">-</button>
                    <span class="px-2 fw-semibold">${item.qty}</span>
                    <button onclick="updateQty(${item.id},1)" class="btn btn-sm btn-outline-secondary py-0 px-2">+</button>
                </div>
                <span class="fw-semibold" style="color:var(--primary)">৳${(item.price*item.qty).toFixed(2)}</span>
            </div>
        </div>`;
    });
    el.innerHTML = html;
    const tax = subtotal * 0.05;
    document.getElementById('subtotalDisp').textContent = '৳' + subtotal.toFixed(2);
    document.getElementById('taxDisp').textContent = '৳' + tax.toFixed(2);
    document.getElementById('totalDisp').textContent = '৳' + (subtotal+tax).toFixed(2);
}
function submitOrder() {
    const items = Object.values(cart);
    if (!items.length) { alert('Add items to cart first!'); return; }
    const form = document.getElementById('orderForm');
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
    form.innerHTML += `<input type="hidden" name="type" value="${document.getElementById('orderType').value}">`;
    const tableId = document.getElementById('tableId').value;
    if (tableId) form.innerHTML += `<input type="hidden" name="table_id" value="${tableId}">`;
    form.innerHTML += `<input type="hidden" name="notes" value="${document.getElementById('orderNotes').value}">`;
    items.forEach((item, i) => {
        form.innerHTML += `<input type="hidden" name="items[${i}][menu_item_id]" value="${item.id}">`;
        form.innerHTML += `<input type="hidden" name="items[${i}][quantity]" value="${item.qty}">`;
    });
    form.submit();
}
// Category filter
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-btn').forEach(b => { b.classList.remove('btn-primary','active'); b.classList.add('btn-outline-secondary'); });
        btn.classList.add('btn-primary','active'); btn.classList.remove('btn-outline-secondary');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.menu-item-wrap').forEach(el => {
            el.style.display = (cat === 'all' || el.dataset.cat === cat) ? '' : 'none';
        });
    });
});
// Search
document.getElementById('menuSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.menu-item-wrap').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
