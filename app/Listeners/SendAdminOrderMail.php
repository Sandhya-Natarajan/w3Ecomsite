<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\AdminOrderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrderPlacedNotification;

class SendAdminOrderMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        /* $order = $event->order;
        $to = config('mail.from.address');

        Mail::to($to)->send(new AdminOrderMail($order));
 */
        $user = $event->order->user;

        $user->notify(new OrderPlacedNotification($event->order));
    }
}
