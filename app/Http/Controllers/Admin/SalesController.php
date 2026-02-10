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

        $orders = Order::with('customer')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->orderByDesc('created_at')
            ->get();

        $paid = $orders->where('payment_status', 'paid');

        $metrics = [
            'total_orders'  => $orders->count(),
            'total_revenue' => $paid->sum('total_price'),
            'cash_total'    => $paid->where('payment_method', 'cash')->sum('total_price'),
            'gcash_total'   => $paid->where('payment_method', 'gcash')->sum('total_price'),
            'maya_total'    => $paid->where('payment_method', 'maya')->sum('total_price'),
            'unpaid_total'  => $orders->where('payment_status', 'unpaid')->sum('total_price'),
        ];

        return view('admin.sales', compact('orders', 'metrics', 'startDate', 'endDate'));
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

        $orders = Order::with('customer')
            ->whereBetween('created_at', ["{$startDate} 00:00:00", "{$endDate} 23:59:59"])
            ->orderByDesc('created_at')
            ->get();

        $filename = "sales-report-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Ticket #', 'Customer', 'Phone', 'Status', 'Total Price', 'Payment Method', 'Payment Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->ticket_number,
                    $order->customer->name,
                    $order->customer->phone,
                    $order->status_label,
                    $order->total_price,
                    $order->payment_method_label,
                    $order->payment_status,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
