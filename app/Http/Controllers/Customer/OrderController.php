<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCancelledNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Cancel a pending-approval order.
     *
     * Only the owning customer may cancel, and only while the order
     * is still in `pending_approval` status.
     */
    public function cancel(Request $request, Order $order)
    {
        if ($request->user()->id !== $order->customer_id || $order->status !== 'pending_approval') {
            abort(403, 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Notify assigned staff (if any) or all staff members
        if ($order->staff) {
            $order->staff->notify(new OrderCancelledNotification($order));
        } else {
            User::role('staff')->get()->each(fn (User $staff) => $staff->notify(new OrderCancelledNotification($order)));
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('customer.dashboard')
            ->with('success', "Order {$order->ticket_number} has been cancelled.");
    }
}
