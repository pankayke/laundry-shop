<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt – {{ $order->ticket_number }}</title>
    <style>
        /* 80mm thermal receipt styling */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; width: 280px; margin: 0 auto; color: #000; }
        .center { text-align: center; }
        .right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .bold { font-weight: bold; }
        .shop-name { font-size: 16px; font-weight: bold; }
        .ticket { font-size: 14px; font-weight: bold; letter-spacing: 1px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .item-row td { font-size: 10px; }
        .total-row td { font-size: 12px; font-weight: bold; padding-top: 4px; }
        .footer { font-size: 9px; margin-top: 12px; }
    </style>
</head>
<body>
    {{-- Shop Header --}}
    <div class="center" style="margin-bottom: 4px;">
        <div class="shop-name">{{ $settings->shop_name ?? 'Laundry Shop' }}</div>
        <div>{{ $settings->shop_address ?? '' }}</div>
        <div>{{ $settings->shop_phone ?? '' }}</div>
    </div>

    <div class="divider"></div>

    {{-- Ticket Info --}}
    <div class="center" style="margin: 6px 0;">
        <div class="ticket">{{ $order->ticket_number }}</div>
        <div>{{ $order->created_at->format('M d, Y h:i A') }}</div>
    </div>

    <div class="divider"></div>

    {{-- Customer --}}
    <table>
        <tr>
            <td class="bold">Customer:</td>
            <td class="right">{{ $order->customer->name }}</td>
        </tr>
        <tr>
            <td class="bold">Phone:</td>
            <td class="right">{{ $order->customer->phone ?? '–' }}</td>
        </tr>
        <tr>
            <td class="bold">Served by:</td>
            <td class="right">{{ $order->staff->name ?? '–' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Items --}}
    <table>
        <tr style="border-bottom: 1px solid #000;">
            <td class="bold">Item</td>
            <td class="bold center">Kg</td>
            <td class="bold center">Svc</td>
            <td class="bold right">Amt</td>
        </tr>
        @foreach ($order->items as $item)
        <tr class="item-row">
            <td>{{ $item->cloth_type }}</td>
            <td class="center">{{ number_format($item->weight, 1) }}</td>
            <td class="center">{{ ucfirst($item->service_type) }}</td>
            <td class="right">{{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    {{-- Totals --}}
    <table>
        <tr>
            <td>Total Weight:</td>
            <td class="right">{{ number_format($order->total_weight, 2) }} kg</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="right">₱{{ number_format($order->total_price, 2) }}</td>
        </tr>
        <tr>
            <td>Payment:</td>
            <td class="right">{{ $order->payment_method_label }}</td>
        </tr>
        <tr>
            <td>Status:</td>
            <td class="right">{{ $order->isPaid() ? 'PAID' : 'UNPAID' }}</td>
        </tr>
        @if ($order->isPaid() && $order->change_amount > 0)
        <tr>
            <td>Amount Paid:</td>
            <td class="right">₱{{ number_format($order->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <td>Change:</td>
            <td class="right">₱{{ number_format($order->change_amount, 2) }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- Order Status --}}
    <div class="center bold" style="margin: 6px 0; font-size: 12px;">
        Status: {{ $order->status_label }}
    </div>

    <div class="divider"></div>

    {{-- Footer --}}
    <div class="center footer">
        <p>Track your order online:</p>
        <p class="bold">{{ url('/track') }}</p>
        <p style="margin-top: 6px;">Thank you for your patronage!</p>
        <p>{{ $settings->shop_name ?? 'Laundry Shop' }}</p>
    </div>
</body>
</html>
