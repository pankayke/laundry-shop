<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReadyNotification extends Notification implements ShouldQueue
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

        // Enable when Twilio SDK is installed and configured:
        // if ($notifiable->phone) {
        //     $channels[] = \NotificationChannels\Twilio\TwilioChannel::class;
        // }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $settings = Setting::instance();

        return (new MailMessage)
            ->subject('GeloWash - Your Laundry is Ready for Pickup!')
            ->greeting("Hi {$notifiable->name}!")
            ->line("Your laundry order **{$this->order->ticket_number}** is ready for pickup!")
            ->line("Visit us at: {$settings->shop_address}")
            ->line('Total: ₱' . number_format($this->order->total_price, 2))
            ->action('Track Order', url("/track/{$this->order->ticket_number}"))
            ->line('Thank you for choosing ' . $settings->shop_name . '!');
    }

    public function toArray(object $notifiable): array
    {
        $settings = Setting::instance();

        return [
            'order_id'      => $this->order->id,
            'ticket_number' => $this->order->ticket_number,
            'message'       => "Your laundry {$this->order->ticket_number} is ready! Visit us at {$settings->shop_address}",
        ];
    }
}
