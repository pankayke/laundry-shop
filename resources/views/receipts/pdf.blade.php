<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt – {{ $order->ticket_number }}</title>
    <style>
        /* 80mm thermal receipt: ~384px width */
        @page { margin: 0; size: 80mm auto; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Courier New', monospace;
            font-size: 11px;
            color: #333;
            width: 72mm;
            margin: 0 auto;
            padding: 8px 0;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .right { text-align: right; }
        .divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 6px 0;
        }
        .header { text-align: center; padding-bottom: 4px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .header p { font-size: 9px; color: #666; line-height: 1.4; }
        .ticket { font-size: 14px; font-weight: bold; text-align: center; padding: 6px 0; letter-spacing: 1px; }
        table { width: 100%; border-collapse: collapse; }
        table th { font-size: 9px; text-transform: uppercase; color: #888; padding: 3px 0; border-bottom: 1px solid #ddd; text-align: left; }
        table td { padding: 3px 0; font-size: 10px; vertical-align: top; }
        .total-row td { font-size: 13px; font-weight: bold; padding-top: 5px; border-top: 1px solid #333; }
        .payment-info { background: #f5f5f5; padding: 6px; border-radius: 4px; margin: 6px 0; }
        .footer { text-align: center; font-size: 9px; color: #999; padding-top: 6px; }
    </style>
</head>
<body>
    {{-- SVG Header Logo --}}
    <div class="header">
        <svg viewBox="0 0 200 60" width="140" style="margin: 0 auto; display: block;">
            <circle cx="30" cy="30" r="22" fill="#E0F6FF" stroke="#4682B4" stroke-width="2"/>
            <circle cx="30" cy="33" r="11" fill="none" stroke="#4682B4" stroke-width="2"/>
            <circle cx="30" cy="33" r="5" fill="#87CEEB"/>
            <rect x="20" y="12" width="20" height="8" rx="4" fill="#FFD700"/>
            <text x="62" y="28" font-size="16" font-weight="bold" fill="#4682B4">Gelo</text>
            <text x="100" y="28" font-size="16" font-weight="bold" fill="#333">Wash</text>
            <text x="62" y="42" font-size="8" fill="#999">Laundry Shop</text>
        </svg>
        <p>{{ $settings->shop_address ?? 'Purok 3, Brgy. San Isidro, GenSan' }}</p>
        <p>{{ $settings->shop_phone ?? '0960-720-4055' }}</p>
    </div>

    <hr class="divider">

    <div class="ticket">{{ $order->ticket_number }}</div>

    <hr class="divider">

    {{-- Order Details --}}
    <table style="margin-bottom: 4px;">
        <tr>
            <td style="color:#888;font-size:9px;">Date</td>
            <td class="right" style="font-size:10px;">{{ $order->created_at->format('M d, Y – g:i A') }}</td>
        </tr>
        <tr>
            <td style="color:#888;font-size:9px;">Customer</td>
            <td class="right" style="font-size:10px;">{{ $order->customer->name ?? 'Walk-in' }}</td>
        </tr>
        <tr>
            <td style="color:#888;font-size:9px;">Staff</td>
            <td class="right" style="font-size:10px;">{{ $order->staff->name ?? '–' }}</td>
        </tr>
        <tr>
            <td style="color:#888;font-size:9px;">Status</td>
            <td class="right" style="font-size:10px;">{{ $order->status_label }}</td>
        </tr>
    </table>

    <hr class="divider">

    {{-- Items --}}
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Service</th>
                <th style="text-align:right;">Wt</th>
                <th style="text-align:right;">Rate</th>
                <th style="text-align:right;">Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->cloth_type }}</td>
                    <td>{{ ucfirst($item->service_type) }}</td>
                    <td class="right">{{ number_format($item->weight, 1) }}kg</td>
                    <td class="right">₱{{ number_format($item->price_per_kg, 0) }}</td>
                    <td class="right bold">₱{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="divider">

    {{-- Total --}}
    <table>
        <tr>
            <td style="color:#888;font-size:9px;">Total Weight</td>
            <td class="right bold">{{ number_format($order->total_weight, 2) }} kg</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL</td>
            <td class="right">₱{{ number_format($order->total_price, 2) }}</td>
        </tr>
    </table>

    {{-- Payment --}}
    @if($order->payment_status === 'paid')
        <div class="payment-info">
            <table>
                <tr>
                    <td style="color:#888;font-size:9px;">Method</td>
                    <td class="right" style="font-size:10px;">{{ ucfirst($order->payment_method) }}</td>
                </tr>
                @if($order->payment_reference)
                    <tr>
                        <td style="color:#888;font-size:9px;">Reference</td>
                        <td class="right" style="font-size:10px;font-family:monospace;">{{ $order->payment_reference }}</td>
                    </tr>
                @endif
                @if(in_array($order->payment_method, ['gcash', 'maya']))
                    <tr>
                        <td style="color:#888;font-size:9px;">Paid to</td>
                        <td class="right" style="font-size:10px;">{{ $settings->gcash_number ?? '09925247231' }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="color:#888;font-size:9px;">Paid</td>
                    <td class="right bold" style="font-size:10px;">₱{{ number_format($order->amount_paid, 2) }}</td>
                </tr>
                @if($order->change_amount > 0)
                    <tr>
                        <td style="color:#888;font-size:9px;">Change</td>
                        <td class="right" style="font-size:10px;">₱{{ number_format($order->change_amount, 2) }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @else
        <div class="payment-info center bold" style="color:#d97706;">UNPAID</div>
    @endif

    <hr class="divider">

    @if($order->notes)
        <p style="font-size:9px;color:#888;margin-bottom:2px;">Notes:</p>
        <p style="font-size:9px;margin-bottom:6px;">{{ $order->notes }}</p>
        <hr class="divider">
    @endif

    <div class="footer">
        <p>Thank you for choosing GeloWash!</p>
        <p style="margin-top:2px;">Track: gelowash.com/track/{{ $order->ticket_number }}</p>
        <p style="margin-top:4px;font-size:8px;color:#bbb;">{{ now()->format('M d, Y g:i A') }}</p>
    </div>
</body>
</html>
