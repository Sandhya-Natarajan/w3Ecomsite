<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Mail\DailyOrderRevenueSummaryMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDailyOrderRevenueSummary extends Command
{
    protected $signature = 'email:daily-order-revenue-summary';
    protected $description = 'Send a daily email showing number of orders and total revenue';

    public function handle()
    {
        $today = now()->toDateString();

        // Get total orders and revenue for today
        $orderCount = Order::whereDate('created_at', $today)->count();
        $totalRevenue = Order::whereDate('created_at', $today)->sum('total_amount');

        $recipient = 'sandhya.w3dreams@gmail.com'; // Change to your admin email

        try {
            // Send email
            Mail::to($recipient)->send(
                new DailyOrderRevenueSummaryMail($orderCount, $totalRevenue, $today)
            );

            // Log success
            Log::channel('daily_summary')->info('Daily order & revenue summary email sent successfully', [
                'date' => $today,
                'orders' => $orderCount,
                'revenue' => $totalRevenue,
                'recipient' => $recipient,
            ]);

            $this->info("Daily summary email sent. Orders: {$orderCount}, Revenue: ₹{$totalRevenue}");
        } catch (\Exception $e) {
            // Log error
            Log::channel('daily_summary')->error('Failed to send daily summary email', [
                'date' => $today,
                'error' => $e->getMessage(),
            ]);

            $this->error('Error sending summary: ' . $e->getMessage());
        }
    }
}
