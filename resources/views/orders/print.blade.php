<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { margin: 0; padding: 20px; font-family: monospace; color: #000; background: #fff; }
        .invoice-container { max-width: 300px; margin: 0 auto; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .small { font-size: 0.85rem; }
        .text-muted { color: #666; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .pt-2 { padding-top: 0.5rem; }
        .pb-1 { padding-bottom: 0.25rem; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .w-100 { width: 100%; }
        .align-top { vertical-align: top; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="text-center mb-3">
            <h2 class="fw-bold mb-1" style="margin-top:0;">{{ env('APP_NAME', 'Restaurant POS') }}</h2>
            <div class="small">Order: {{ $order->order_number }}</div>
            <div class="small">Date: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            @if($order->tables->count() > 0)<div class="small">Table(s): {{ $order->tables->pluck('table_number')->implode(', ') }}</div>@endif
            @if($order->customer)<div class="small">Customer: {{ $order->customer->name }}</div>@endif
        </div>
        <div class="border-top border-bottom py-2 mb-2">
            <table class="w-100 small" style="border-collapse: collapse;">
                @foreach($order->items as $item)
                <tr>
                    <td class="pb-1">
                        {{ $item->item_name }}<br>
                        <small class="text-muted">{{ $item->quantity }} x {{ number_format($item->unit_price, 2) }}</small>
                    </td>
                    <td class="text-end align-top pb-1">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <table class="w-100 small mb-2" style="border-collapse: collapse;">
            <tr><td>Subtotal</td><td class="text-end">{{ number_format($order->subtotal, 2) }}</td></tr>
            <tr><td>Tax</td><td class="text-end">{{ number_format($order->tax_amount, 2) }}</td></tr>
            @if($order->discount_amount > 0)
            <tr><td>Discount</td><td class="text-end">-{{ number_format($order->discount_amount, 2) }}</td></tr>
            @endif
            @if($order->coupon_discount > 0)
            <tr><td>Coupon</td><td class="text-end">-{{ number_format($order->coupon_discount, 2) }}</td></tr>
            @endif
        </table>
        <div class="border-top pt-2 mb-3" style="display:flex; justify-content:space-between; font-weight:bold; font-size:1.1rem;">
            <span>TOTAL</span>
            <span>{{ number_format($order->total_amount, 2) }}</span>
        </div>
        
        @if($order->payment)
        <div class="text-center small mb-3">
            PAID via {{ strtoupper(str_replace('_', ' ', $order->payment->method)) }}
        </div>
        @endif

        <div class="text-center small border-top pt-2">
            Thank you for your visit!<br>Please come again.
        </div>
        
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; border: 1px solid #ccc; background: #eee; font-family: inherit;">Print Receipt</button>
            <br><br>
            <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; border: none; background: transparent; text-decoration: underline; color: #666; font-family: inherit;">Close Window</button>
        </div>
    </div>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
