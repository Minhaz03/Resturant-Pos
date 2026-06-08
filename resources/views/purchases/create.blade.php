@extends('layouts.app')
@section('title','New Purchase Order')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">New Purchase Order</h4>
</div>
<form method="POST" action="{{ route('purchases.store') }}" id="poForm">@csrf
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">PO Details</div>
            <div class="card-body">
                <div class="mb-3"><label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                    </select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Order Date</label><input type="date" name="order_date" class="form-control" value="{{ old('order_date',today()->toDateString()) }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Expected Date</label><input type="date" name="expected_date" class="form-control" value="{{ old('expected_date') }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select"><option value="draft">Draft</option><option value="ordered">Ordered</option></select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Notes</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span id="subtotalDisplay">৳0.00</span></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Tax</span><span id="taxDisplay">৳0.00</span></div>
                    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span id="totalDisplay" style="color:var(--primary)">৳0.00</span></div>
                    <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                    <input type="hidden" name="tax_amount" id="taxInput" value="0">
                    <input type="hidden" name="total_amount" id="totalInput" value="0">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Order Items</span>
                <button type="button" class="btn btn-sm btn-primary" onclick="addItem()"><i class="bi bi-plus-lg me-1"></i>Add Item</button>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" id="itemsTable">
                    <thead><tr><th>Item</th><th>Quantity</th><th>Unit</th><th>Unit Price</th><th>Total</th><th></th></tr></thead>
                    <tbody id="itemsBody">
                        <tr id="noItemRow"><td colspan="6" class="text-center py-4 text-muted">No items added yet. Click "Add Item" to start.</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Purchase Order</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>

<template id="itemRowTemplate">
    <tr class="item-row">
        <td><select name="items[INDEX][inventory_item_id]" class="form-select form-select-sm item-select" required>
            <option value="">Select Item</option>
            @foreach($inventoryItems as $inv)<option value="{{ $inv->id }}" data-unit="{{ $inv->unit }}" data-cost="{{ $inv->unit_cost }}">{{ $inv->name }}</option>@endforeach
        </select></td>
        <td><input type="number" name="items[INDEX][quantity]" class="form-control form-control-sm item-qty" placeholder="0" step="0.01" min="0.01" required></td>
        <td><input type="text" name="items[INDEX][unit]" class="form-control form-control-sm item-unit" placeholder="kg" readonly></td>
        <td><input type="number" name="items[INDEX][unit_price]" class="form-control form-control-sm item-price" placeholder="0.00" step="0.01" min="0" required></td>
        <td class="fw-semibold item-total">৳0.00<input type="hidden" name="items[INDEX][total_price]" class="item-total-input" value="0"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeItem(this)"><i class="bi bi-x"></i></button></td>
    </tr>
</template>

@push('scripts')
<script>
let idx = 0;
function addItem() {
    document.getElementById('noItemRow').style.display='none';
    const tmpl = document.getElementById('itemRowTemplate').innerHTML.replace(/INDEX/g, idx);
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = tmpl.replace('<tr class="item-row">','').replace('</tr>','');
    const tbody = document.getElementById('itemsBody');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = tmpl.match(/<tr class="item-row">([\s\S]*?)<\/tr>/)[1];
    tbody.appendChild(newRow);

    newRow.querySelector('.item-select').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        newRow.querySelector('.item-unit').value = opt.dataset.unit || '';
        newRow.querySelector('.item-price').value = opt.dataset.cost || '';
        calcRow(newRow); calcTotal();
    });
    newRow.querySelector('.item-qty').addEventListener('input', () => { calcRow(newRow); calcTotal(); });
    newRow.querySelector('.item-price').addEventListener('input', () => { calcRow(newRow); calcTotal(); });
    idx++;
}
function calcRow(row) {
    const qty = parseFloat(row.querySelector('.item-qty').value)||0;
    const price = parseFloat(row.querySelector('.item-price').value)||0;
    const total = qty * price;
    row.querySelector('.item-total').firstChild.textContent = '৳'+total.toFixed(2);
    row.querySelector('.item-total-input').value = total.toFixed(2);
}
function removeItem(btn) {
    btn.closest('tr').remove();
    if(!document.querySelectorAll('.item-row').length) document.getElementById('noItemRow').style.display='';
    calcTotal();
}
function calcTotal() {
    let sub = 0;
    document.querySelectorAll('.item-total-input').forEach(i => sub += parseFloat(i.value)||0);
    const tax = 0;
    document.getElementById('subtotalDisplay').textContent = '৳'+sub.toFixed(2);
    document.getElementById('taxDisplay').textContent = '৳'+tax.toFixed(2);
    document.getElementById('totalDisplay').textContent = '৳'+(sub+tax).toFixed(2);
    document.getElementById('subtotalInput').value = sub.toFixed(2);
    document.getElementById('taxInput').value = tax.toFixed(2);
    document.getElementById('totalInput').value = (sub+tax).toFixed(2);
}
</script>
@endpush
@endsection
