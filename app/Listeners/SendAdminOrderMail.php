<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\AdminOrderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $order = $event->order;
        $to = config('mail.from.address');

        Mail::to($to)->send(new AdminOrderMail($order));
    }
}
