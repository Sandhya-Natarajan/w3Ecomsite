<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyOrderRevenueSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderCount;
    public $totalRevenue;
    public $date;

    public function __construct($orderCount, $totalRevenue, $date)
    {
        $this->orderCount = $orderCount;
        $this->totalRevenue = $totalRevenue;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject("Daily Summary for {$this->date}")
            ->view('daily_summary');
    }
}
