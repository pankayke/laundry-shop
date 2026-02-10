<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; }
        .meta { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; font-size: 10px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; }
        .summary td { border: none; padding: 3px 8px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $settings->shop_name }} – Sales Report</h1>
    <p class="meta">{{ $startDate }} to {{ $endDate }} | Generated: {{ now()->format('M d, Y h:i A') }}</p>

    <table class="summary">
        <tr><td>Total Orders:</td><td class="bold">{{ $metrics['total_orders'] }}</td></tr>
        <tr><td>Total Revenue:</td><td class="bold">₱{{ number_format($metrics['total_revenue'], 2) }}</td></tr>
        <tr><td>Cash:</td><td>₱{{ number_format($metrics['cash_total'], 2) }}</td></tr>
        <tr><td>GCash:</td><td>₱{{ number_format($metrics['gcash_total'], 2) }}</td></tr>
        <tr><td>Maya:</td><td>₱{{ number_format($metrics['maya_total'], 2) }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Ticket #</th>
                <th>Customer</th>
                <th>Status</th>
                <th class="text-right">Weight</th>
                <th class="text-right">Total</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->ticket_number }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->status_label }}</td>
                    <td class="text-right">{{ $order->total_weight }} kg</td>
                    <td class="text-right">₱{{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->payment_method_label }}</td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
