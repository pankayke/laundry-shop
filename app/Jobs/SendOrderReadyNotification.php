<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderReadyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderReadyNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        private Order $order,
    ) {}

    public function handle(): void
    {
        $this->order->load('customer');
        $this->order->customer->notify(new OrderReadyNotification($this->order));
    }
}
