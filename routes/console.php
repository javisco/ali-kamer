<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Order;
use App\Notifications\Shippings\BuyerPickupReminderNotification;
use App\Services\OrderService;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Toutes les nuits à 02h00 — auto-complete les commandes expirées
Schedule::call(function () {
    app(OrderService::class)->autoCompleteExpired();
})->dailyAt('02:00');

Schedule::command('orders:warn-unshipped')
    ->hourly();


// RAPPELS ACHETEUR — RETRAIT DE COMMANDE || | Tous les jours à 09h00. | | 24h → rappel de retrait | 48h → rappel urgent | */
//  | Commandes arrivées depuis 24h sans retrait |-


Schedule::call(function () {
    Order::where('status', 'awaiting_buyer_confirmation')
        ->where('arrived_at', '<=', now()->subHours(24))
        ->where('arrived_at', '>', now()->subHours(48))
        ->with('shop.user')->each(function ($order) {
            $user = $order->shop?->user;
            if (!$user) {
                return;
            }
            $user->notify(new BuyerPickupReminderNotification($order, 48));
        }); //Commandes arrivées depuis 48h sans retrait |
    Order::where('status', 'awaiting_buyer_confirmation')
        ->where('arrived_at', '<=', now()->subHours(48))
        ->where('arrived_at', '>', now()->subHours(72))
        ->with('shop.user')->each(function ($order) {
            $user = $order->shop?->user;
            if (!$user) {
                return;
            }
            $user->notify(new BuyerPickupReminderNotification($order, 24));
        });
})->dailyAt('09:00');
