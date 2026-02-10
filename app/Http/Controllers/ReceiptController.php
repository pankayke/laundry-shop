<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download(Order $order)
    {
        $order->load('items', 'customer');
        $settings = Setting::instance();

        $pdf = Pdf::loadView('receipts.pdf', compact('order', 'settings'));
        $pdf->setPaper([0, 0, 226.77, 600], 'portrait'); // 80 mm thermal receipt width

        return $pdf->download("receipt-{$order->ticket_number}.pdf");
    }
}
