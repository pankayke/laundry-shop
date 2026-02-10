<?php

namespace App\Services;

use App\Models\Order;

class TicketNumberService
{
    /** Generate the next sequential ticket: LAUN-{YYYY}-{0001}. */
    public function generate(): string
    {
        $year   = date('Y');
        $prefix = "LAUN-{$year}-";

        $lastOrder = Order::withTrashed()
            ->where('ticket_number', 'like', $prefix . '%')
            ->orderByDesc('ticket_number')
            ->first();

        $nextNumber = $lastOrder
            ? (int) substr($lastOrder->ticket_number, strlen($prefix)) + 1
            : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
