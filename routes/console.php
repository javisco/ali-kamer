<?php

//use Illuminate\Foundation\Inspiring;
// use Illuminate\Support\Facades\Artisan;
// use App\Services\OrderService;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


// Schedule::call(function () {
//     app(OrderService::class)->autoCompleteExpired();
// })->dailyAt('02:00');



use App\Models\Order;
use App\Services\NotificationService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Schedule;

// Toutes les nuits à 02h00 — auto-complete les commandes expirées
Schedule::call(function () {
    app(OrderService::class)->autoCompleteExpired();
})->dailyAt('02:00');

// Toutes les nuits à 09h00 — relance les acheteurs qui n'ont pas retiré
Schedule::call(function () {
    // Commandes arrivées depuis 24h sans retrait
    Order::where('status', 'awaiting_buyer_confirmation')
        ->where('arrived_at', '<=', now()->subHours(24))
        ->where('arrived_at', '>', now()->subHours(48))
        ->each(function ($order) {
            app(NotificationService::class)->notifyPickupReminder($order, 48);
        });

    // Commandes arrivées depuis 48h sans retrait
    Order::where('status', 'awaiting_buyer_confirmation')
        ->where('arrived_at', '<=', now()->subHours(48))
        ->where('arrived_at', '>', now()->subHours(72))
        ->each(function ($order) {
            app(NotificationService::class)->notifyPickupReminder($order, 24);
        });
})->dailyAt('09:00');

// Toutes les nuits à 10h00 — avertir les vendeurs qui n'ont pas expédié
Schedule::call(function () {
    Order::where('status', 'paid')
        ->where('paid_at', '<=', now()->subHours(48))
        ->each(function ($order) {
            app(NotificationService::class)->notifySellerShippingWarning($order);
        });
})->dailyAt('10:00');
