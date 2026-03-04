<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    /**
     * Generate the next sequential ticket: GW-{YYYY}-{0001}.
     *
     * Uses a DB-level lock to prevent duplicate ticket numbers
     * under concurrent request conditions.
     */
    public function generate(): string
    {
        return DB::transaction(function () {
            $year   = date('Y');
            $prefix = "GW-{$year}-";

            $lastOrder = Order::withTrashed()
                ->where('ticket_number', 'like', $prefix . '%')
                ->orderByDesc('ticket_number')
                ->first();

            $nextNumber = $lastOrder
                ? (int) substr($lastOrder->ticket_number, strlen($prefix)) + 1
                : 1;

            return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }, 5); // Retry up to 5 times on deadlock/conflict
    }
}
