<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sales Report</title>
<style>
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1a1a2e; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 3px solid #8B0000; padding-bottom: 15px; margin-bottom: 20px; }
    .header h1 { font-size: 20px; color: #8B0000; margin: 0 0 5px; }
    .header p { margin: 2px 0; color: #555; font-size: 10px; }
    .meta { display: flex; justify-content: space-between; margin-bottom: 15px; background: #f5f7fa; padding: 10px; border-radius: 4px; }
    .summary-grid { display: flex; gap: 10px; margin-bottom: 20px; }
    .summary-box { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 10px; text-align: center; }
    .summary-box .label { font-size: 9px; color: #777; text-transform: uppercase; }
    .summary-box .value { font-size: 16px; font-weight: bold; color: #8B0000; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #0A2647; color: white; padding: 8px 10px; text-align: left; font-size: 10px; }
    td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
    tr:nth-child(even) td { background: #f9f9f9; }
    tfoot td { font-weight: bold; background: #f5f7fa; border-top: 2px solid #0A2647; }
    .footer { text-align: center; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; color: #777; font-size: 9px; }
</style>
</head>
<body>
<div class="header">
    <h1>Sales Report</h1>
    <p>{{ $restaurantName ?? 'The Grand Restaurant' }}</p>
    <p>Period: {{ $from }} to {{ $to }}</p>
</div>
<div class="summary-grid">
    <div class="summary-box"><div class="label">Total Revenue</div><div class="value">৳{{ number_format($summary['total_revenue'] ?? 0,2) }}</div></div>
    <div class="summary-box"><div class="label">Total Orders</div><div class="value">{{ $summary['total_orders'] ?? 0 }}</div></div>
    <div class="summary-box"><div class="label">Avg Order Value</div><div class="value">৳{{ number_format($summary['avg_order_value'] ?? 0,2) }}</div></div>
    <div class="summary-box"><div class="label">Total Tax</div><div class="value">৳{{ number_format($summary['total_tax'] ?? 0,2) }}</div></div>
</div>
<table>
    <thead><tr><th>Period</th><th>Orders</th><th>Revenue</th><th>Tax</th><th>Discount</th><th>Net Revenue</th></tr></thead>
    <tbody>
        @foreach($salesData as $row)
        <tr>
            <td>{{ $row['period'] }}</td>
            <td>{{ $row['orders'] }}</td>
            <td>৳{{ number_format($row['revenue'],2) }}</td>
            <td>৳{{ number_format($row['tax'] ?? 0,2) }}</td>
            <td>৳{{ number_format($row['discount'] ?? 0,2) }}</td>
            <td>৳{{ number_format($row['net'] ?? $row['revenue'],2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><td><strong>Total</strong></td><td>{{ $summary['total_orders'] ?? 0 }}</td>
            <td>৳{{ number_format($summary['total_revenue'] ?? 0,2) }}</td>
            <td>৳{{ number_format($summary['total_tax'] ?? 0,2) }}</td>
            <td>৳{{ number_format($summary['total_discount'] ?? 0,2) }}</td>
            <td>৳{{ number_format(($summary['total_revenue'] ?? 0) - ($summary['total_tax'] ?? 0) - ($summary['total_discount'] ?? 0),2) }}</td>
        </tr>
    </tfoot>
</table>
<div class="footer">
    Generated on {{ now()->format('d M Y, h:i A') }} &bull; {{ $restaurantName ?? 'Restaurant Management System' }}
</div>
</body>
</html>
