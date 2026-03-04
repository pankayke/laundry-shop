<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        $dateRange = ["{$startDate} 00:00:00", "{$endDate} 23:59:59"];

        // DB-level aggregates — avoids loading all orders into memory for metrics
        $metrics = Order::whereBetween('created_at', $dateRange)
            ->selectRaw("COUNT(*) as total_orders")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as total_revenue")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'cash'  THEN total_price ELSE 0 END) as cash_total")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'gcash' THEN total_price ELSE 0 END) as gcash_total")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'maya'  THEN total_price ELSE 0 END) as maya_total")
            ->selectRaw("SUM(CASE WHEN payment_status != 'paid' THEN total_price ELSE 0 END) as unpaid_total")
            ->first();

        $metricsArray = [
            'total_orders'  => $metrics->total_orders,
            'total_revenue' => $metrics->total_revenue ?? 0,
            'cash_total'    => $metrics->cash_total ?? 0,
            'gcash_total'   => $metrics->gcash_total ?? 0,
            'maya_total'    => $metrics->maya_total ?? 0,
            'unpaid_total'  => $metrics->unpaid_total ?? 0,
        ];

        $orders = Order::with('customer')
            ->whereBetween('created_at', $dateRange)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.sales', ['orders' => $orders, 'metrics' => $metricsArray, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        $orders = Order::with('customer')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->where('payment_status', 'paid')
            ->orderByDesc('created_at')
            ->get();

        $metrics = [
            'total_orders'  => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'cash_total'    => $orders->where('payment_method', 'cash')->sum('total_price'),
            'gcash_total'   => $orders->where('payment_method', 'gcash')->sum('total_price'),
            'maya_total'    => $orders->where('payment_method', 'maya')->sum('total_price'),
        ];

        $settings = Setting::instance();

        $pdf = Pdf::loadView('admin.sales-pdf', compact('orders', 'metrics', 'startDate', 'endDate', 'settings'));

        return $pdf->download("sales-report-{$startDate}-to-{$endDate}.pdf");
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        $filename = "sales-report-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        // Stream CSV using cursor to keep memory flat for large exports
        $callback = function () use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Ticket #', 'Customer', 'Phone', 'Status', 'Total Price', 'Payment Method', 'Payment Status', 'Date']);

            Order::with('customer')
                ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
                ->orderByDesc('created_at')
                ->lazy(200)
                ->each(function (Order $order) use ($file) {
                    fputcsv($file, [
                        $order->ticket_number,
                        $order->customer?->name ?? 'Walk-in',
                        $order->customer?->phone ?? '',
                        $order->status_label,
                        $order->total_price,
                        $order->payment_method_label,
                        $order->payment_status,
                        $order->created_at->format('Y-m-d H:i'),
                    ]);
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
