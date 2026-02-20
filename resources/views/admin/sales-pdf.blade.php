<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report – GeloWash</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        .header { background: #87CEEB; color: white; padding: 20px; text-align: center; }
        .header h1 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 10px; opacity: 0.9; }
        .info { padding: 15px 20px; background: #f7f7f7; border-bottom: 1px solid #e5e5e5; }
        .info p { font-size: 10px; color: #666; margin-bottom: 2px; }
        .metrics { display: table; width: 100%; padding: 15px 20px; }
        .metric { display: table-cell; text-align: center; padding: 8px; }
        .metric-value { font-size: 16px; font-weight: bold; color: #333; }
        .metric-label { font-size: 9px; color: #888; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #87CEEB; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #fafafa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .paid { color: #16a34a; }
        .unpaid { color: #d97706; }
        .footer { margin-top: 20px; padding: 15px 20px; border-top: 1px solid #e5e5e5; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings->shop_name ?? 'GeloWash Laundry Shop' }}</h1>
        <p>{{ $settings->shop_address ?? '' }} | {{ $settings->shop_phone ?? '' }}</p>
        <p style="margin-top: 8px; font-size: 13px; font-weight: bold;">SALES REPORT</p>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>

    <div class="metrics">
        <div class="metric">
            <div class="metric-value">{{ number_format($metrics['total_orders']) }}</div>
            <div class="metric-label">Orders</div>
        </div>
        <div class="metric">
            <div class="metric-value">₱{{ number_format($metrics['total_revenue'], 2) }}</div>
            <div class="metric-label">Revenue</div>
        </div>
        <div class="metric">
            <div class="metric-value">₱{{ number_format($metrics['cash_total'], 2) }}</div>
            <div class="metric-label">Cash</div>
        </div>
        <div class="metric">
            <div class="metric-value">₱{{ number_format($metrics['gcash_total'], 2) }}</div>
            <div class="metric-label">GCash</div>
        </div>
        <div class="metric">
            <div class="metric-value">₱{{ number_format($metrics['maya_total'], 2) }}</div>
            <div class="metric-label">Maya</div>
        </div>
    </div>

    <div style="padding: 0 20px;">
        <table>
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th class="text-right">Weight</th>
                    <th class="text-right">Total</th>
                    <th>Payment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight:bold;">{{ $order->ticket_number }}</td>
                        <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $order->status_label }}</td>
                        <td class="text-right">{{ number_format($order->total_weight, 2) }} kg</td>
                        <td class="text-right" style="font-weight:bold;">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="{{ $order->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                            {{ ucfirst($order->payment_status) }}
                            @if($order->payment_method)
                                ({{ ucfirst($order->payment_method) }})
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('M d, g:ia') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px;">No orders</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generated on {{ now()->format('M d, Y – g:i A') }} | {{ $settings->shop_name ?? 'GeloWash Laundry Shop' }}
    </div>
</body>
</html>
