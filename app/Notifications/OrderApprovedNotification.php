<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Order $order,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->email) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $settings = Setting::instance();

        return (new MailMessage)
            ->subject('GeloWash - Your Laundry Request Has Been Approved!')
            ->greeting("Hi {$notifiable->name}!")
            ->line("Your laundry request **{$this->order->ticket_number}** has been approved and is now being processed.")
            ->line('Estimated weight: ' . number_format($this->order->estimated_weight ?? $this->order->total_weight, 2) . ' kg')
            ->line('Estimated total: ₱' . number_format($this->order->total_price, 2))
            ->action('Track Order', url("/track/{$this->order->ticket_number}"))
            ->line('Thank you for choosing ' . $settings->shop_name . '!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'      => $this->order->id,
            'ticket_number' => $this->order->ticket_number,
            'message'       => "Your laundry request {$this->order->ticket_number} has been approved!",
        ];
    }
}
