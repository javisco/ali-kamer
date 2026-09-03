@extends('layouts.buyer')

@section('title', 'Tableau de bord - Acheteur')

@section('content')

<div class="mx-auto max-w-[1400px] px-4 py-5 sm:px-6 lg:px-7">

    {{-- =========================================================
        EN-TÊTE
    ========================================================== --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="mb-1.5 flex items-center gap-2">
                <span class="inline-flex items-center rounded-md bg-[#016837]/10 px-2 py-1
                             text-[9px] font-bold uppercase tracking-wider text-[#016837]">
                    Espace acheteur
                </span>

                <span class="h-1 w-1 rounded-full bg-[#F9A01B]"></span>

                <span class="text-[9px] font-medium text-slate-400">
                    Mon activité
                </span>
            </div>

            <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">
                Bonjour, {{ auth()->user()->name }} 👋
            </h1>

            <p class="mt-1 max-w-2xl text-[11px] leading-relaxed text-slate-500 sm:text-xs">
                Retrouvez ici vos commandes, vos dépenses et le suivi de vos achats
                en quelques secondes.
            </p>
        </div>

        {{-- Bouton marketplace --}}
        <a href="/"
           class="inline-flex w-fit items-center gap-2 rounded-lg bg-[#016837] px-3.5 py-2
                  text-[10px] font-bold text-white shadow-sm shadow-[#016837]/20
                  transition hover:-translate-y-0.5 hover:bg-[#015a30]">

            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 12h18M12 3v18"/>
            </svg>

            Continuer mes achats
        </a>
    </div>


    {{-- =========================================================
        STATISTIQUES
    ========================================================== --}}
    <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">

        {{-- Commandes en cours --}}
        <div class="group relative overflow-hidden rounded-xl border border-slate-200
                    bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="absolute right-0 top-0 h-16 w-16 rounded-bl-full bg-[#016837]/5"></div>

            <div class="relative flex items-start justify-between">

                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                        Commandes en cours
                    </p>

                    <p class="mt-1 text-xl font-extrabold text-[#016837]">
                        {{ $stats['active_orders'] }}
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        À suivre
                    </p>
                </div>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#016837]/10 text-[#016837]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 7h18M5 7l1.5 12h11L19 7M9 7V5a3 3 0 016 0v2"/>
                    </svg>
                </div>
            </div>
        </div>


        {{-- Commandes terminées --}}
        <div class="group relative overflow-hidden rounded-xl border border-slate-200
                    bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="absolute right-0 top-0 h-16 w-16 rounded-bl-full bg-[#016837]/5"></div>

            <div class="relative flex items-start justify-between">

                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                        Livrées & terminées
                    </p>

                    <p class="mt-1 text-xl font-extrabold text-[#016837]">
                        {{ $stats['completed_orders'] }}
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Achats réussis
                    </p>
                </div>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#016837]/10 text-[#016837]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>


        {{-- Litiges --}}
        <div class="group relative overflow-hidden rounded-xl border border-slate-200
                    bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="absolute right-0 top-0 h-16 w-16 rounded-bl-full bg-[#E30613]/5"></div>

            <div class="relative flex items-start justify-between">

                <div>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                        Litiges / réclamations
                    </p>

                    <p class="mt-1 text-xl font-extrabold text-[#E30613]">
                        {{ $stats['disputed_orders'] }}
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Nécessitent une attention
                    </p>
                </div>

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E30613]/10 text-[#E30613]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 9v4M12 17h.01M10.3 4.7l-7 12A2 2 0 005 20h14a2 2 0 001.7-3.3l-7-12a2 2 0 00-3.4 0z"/>
                    </svg>
                </div>
            </div>
        </div>


        {{-- Total dépensé --}}
        <div class="group relative overflow-hidden rounded-xl border border-slate-200
                    bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="absolute right-0 top-0 h-16 w-16 rounded-bl-full bg-[#F9A01B]/10"></div>

            <div class="relative flex items-start justify-between">

                <div class="min-w-0">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">
                        Total dépensé
                    </p>

                    <p class="mt-1 truncate text-lg font-extrabold text-slate-900">
                        {{ number_format($stats['total_spent'], 0, ',', ' ') }}
                        <span class="text-[10px] text-[#F9A01B]">FCFA</span>
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Sur Ali-Kamer
                    </p>
                </div>

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#F9A01B]/15 text-[#b87500]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 3v18M16 7.5c0-1.7-1.8-3-4-3s-4 1.3-4 3 1.8 3 4 3 4 1.3 4 3-1.8 3-4 3-4-1.3-4-3"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        ACTIONS RAPIDES
    ========================================================== --}}
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

        {{-- Historique --}}
        <a href="{{ route('buyer.wallet.history') }}"
           class="group flex items-center justify-between rounded-xl border border-slate-200
                  bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#016837]/20
                  hover:shadow-md">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#016837]/10 text-[#016837]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 12a9 9 0 1018 0A9 9 0 003 12zM12 7v5l3 2"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-800">
                        Historique des dépenses
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Consultez vos paiements et dépenses
                    </p>
                </div>
            </div>

            <span class="flex h-7 w-7 items-center justify-center rounded-lg
                         bg-slate-50 text-slate-400 transition
                         group-hover:bg-[#016837] group-hover:text-white">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>


        {{-- Profil --}}
        <a href="{{ route('buyer.profile') }}"
           class="group flex items-center justify-between rounded-xl border border-slate-200
                  bg-white p-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#F9A01B]/30
                  hover:shadow-md">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#F9A01B]/15 text-[#b87500]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M20 21a8 8 0 00-16 0M12 13a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-slate-800">
                        Mon profil
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Gérez vos informations personnelles
                    </p>
                </div>
            </div>

            <span class="flex h-7 w-7 items-center justify-center rounded-lg
                         bg-slate-50 text-slate-400 transition
                         group-hover:bg-[#F9A01B] group-hover:text-white">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        </a>

    </div>


    {{-- =========================================================
        COMMANDE EN COURS
    ========================================================== --}}
    @if ($activeOrder)

        <div class="relative mb-5 overflow-hidden rounded-2xl bg-[#0A1B12] shadow-lg">

            {{-- Décor --}}
            <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-[#016837]/30"></div>
            <div class="absolute -bottom-20 left-1/3 h-40 w-40 rounded-full bg-[#F9A01B]/10"></div>

            <div class="relative p-4 sm:p-5">

                {{-- Header --}}
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10">
                            <svg class="h-5 w-5 text-[#F9A01B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 7h18M5 7l1.5 12h11L19 7M9 7V5a3 3 0 016 0v2"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-[0.14em] text-[#F9A01B]">
                                Commande en cours
                            </p>

                            <h2 class="mt-0.5 text-sm font-extrabold text-white">
                                Votre colis est en route
                            </h2>
                        </div>

                    </div>

                    <span class="inline-flex w-fit items-center rounded-full bg-[#F9A01B]/15
                                 px-2.5 py-1 text-[9px] font-bold text-[#F9A01B]">

                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-[#F9A01B]"></span>

                        En suivi
                    </span>

                </div>


                {{-- Informations commande --}}
                <div class="grid gap-2 sm:grid-cols-3">

                    <div class="rounded-xl border border-white/10 bg-white/[0.045] p-3">
                        <p class="text-[8px] font-bold uppercase tracking-wider text-white/35">
                            Référence
                        </p>

                        <p class="mt-1 text-[11px] font-bold text-white">
                            {{ $activeOrder->reference }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-white/[0.045] p-3">
                        <p class="text-[8px] font-bold uppercase tracking-wider text-white/35">
                            Vendeur
                        </p>

                        <p class="mt-1 truncate text-[11px] font-bold text-white">
                            {{ $activeOrder->shop->name }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-white/[0.045] p-3">
                        <p class="text-[8px] font-bold uppercase tracking-wider text-white/35">
                            Destination
                        </p>

                        <p class="mt-1 text-[11px] font-bold text-white">
                            {{ $activeOrder->shipment->destination_city }}
                        </p>
                    </div>

                </div>


                {{-- =================================================
                    OTP / SUIVI
                ================================================== --}}
                <div class="mt-3">

                    @if (
                        $activeOrder->status === \App\Models\Order::STATUS_ARRIVED_DESTINATION ||
                        $activeOrder->status === \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION
                    )

                        <div class="flex flex-col gap-3 rounded-xl border border-[#F9A01B]/20
                                    bg-[#F9A01B]/10 p-3.5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                                            bg-[#F9A01B] text-[#0A1B12]">

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 11V7m0 8h.01M5.07 19a9 9 0 1113.86 0"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold text-[#F9A01B]">
                                        Code OTP de retrait
                                    </p>

                                    <p class="mt-0.5 text-[9px] text-white/50">
                                        Présentez ce code au guichet pour récupérer votre colis.
                                    </p>
                                </div>

                            </div>

                            <div class="flex items-center gap-2">

                                <div class="rounded-lg bg-white px-3 py-2 text-center">
                                    <span class="text-lg font-black tracking-[0.25em] text-[#0A1B12]">
                                        {{ $activeOrder->otp_code }}
                                    </span>
                                </div>

                            </div>
                        </div>

                    @else

                        <a href="{{ route('buyer.orders.show', $activeOrder) }}"
                           class="group flex items-center justify-between rounded-xl border border-white/10
                                  bg-white/[0.045] p-3 transition hover:bg-white/[0.08]">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#016837]">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold text-white">
                                        Suivre votre colis
                                    </p>

                                    <p class="mt-0.5 text-[9px] text-white/40">
                                        Consultez l'avancement détaillé de votre commande.
                                    </p>
                                </div>

                            </div>

                            <svg class="h-4 w-4 text-white/30 transition group-hover:translate-x-1 group-hover:text-[#F9A01B]"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7"/>
                            </svg>

                        </a>

                    @endif

                </div>

            </div>
        </div>

    @endif


    {{-- =========================================================
        COMMANDES RÉCENTES
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3.5">

            <div>
                <p class="text-[9px] font-bold uppercase tracking-wider text-[#016837]">
                    Activité
                </p>

                <h2 class="mt-0.5 text-sm font-extrabold text-slate-900">
                    Commandes récentes
                </h2>
            </div>

            <a href="{{ route('buyer.orders.index') }}"
               class="group inline-flex items-center gap-1 text-[10px] font-bold text-[#016837] hover:text-[#014d2c]">

                Voir tout

                <svg class="h-3 w-3 transition group-hover:translate-x-0.5"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>

        </div>


        @if ($recentOrders->count() > 0)

            {{-- =================================================
                VERSION DESKTOP
            ================================================== --}}
            <div class="hidden overflow-x-auto md:block">

                <table class="w-full">

                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">

                            <th class="px-4 py-2.5 text-left text-[8px] font-bold uppercase tracking-wider text-slate-400">
                                Référence
                            </th>

                            <th class="px-4 py-2.5 text-left text-[8px] font-bold uppercase tracking-wider text-slate-400">
                                Boutique
                            </th>

                            <th class="px-4 py-2.5 text-left text-[8px] font-bold uppercase tracking-wider text-slate-400">
                                Montant
                            </th>

                            <th class="px-4 py-2.5 text-left text-[8px] font-bold uppercase tracking-wider text-slate-400">
                                Statut
                            </th>

                            <th class="px-4 py-2.5 text-left text-[8px] font-bold uppercase tracking-wider text-slate-400">
                                Date
                            </th>

                            <th class="px-4 py-2.5"></th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach ($recentOrders as $order)

                            @php
                                $statusClass = match ($order->status) {
                                    \App\Models\Order::STATUS_PENDING =>
                                        'bg-slate-100 text-slate-600',

                                    \App\Models\Order::STATUS_AWAITING_PAYMENT =>
                                        'bg-[#F9A01B]/15 text-[#9a6400]',

                                    \App\Models\Order::STATUS_PAID,
                                    \App\Models\Order::STATUS_PREPARING,
                                    \App\Models\Order::STATUS_IN_TRANSIT =>
                                        'bg-[#016837]/10 text-[#016837]',

                                    \App\Models\Order::STATUS_REGISTERED_ORIGIN,
                                    \App\Models\Order::STATUS_ARRIVED_DESTINATION =>
                                        'bg-[#F9A01B]/15 text-[#9a6400]',

                                    \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION =>
                                        'bg-[#F9A01B]/20 text-[#8a5800]',

                                    \App\Models\Order::STATUS_COMPLETED,
                                    \App\Models\Order::STATUS_AUTO_COMPLETED =>
                                        'bg-[#016837]/10 text-[#016837]',

                                    \App\Models\Order::STATUS_DISPUTED =>
                                        'bg-[#E30613]/10 text-[#E30613]',

                                    \App\Models\Order::STATUS_CANCELLED,
                                    \App\Models\Order::STATUS_FAILED =>
                                        'bg-slate-100 text-slate-500',

                                    default =>
                                        'bg-slate-100 text-slate-600',
                                };

                                $statusLabel = match ($order->status) {
                                    \App\Models\Order::STATUS_PENDING => 'En attente',
                                    \App\Models\Order::STATUS_AWAITING_PAYMENT => 'Paiement requis',
                                    \App\Models\Order::STATUS_PAID => 'Payée',
                                    \App\Models\Order::STATUS_PREPARING => 'En préparation',
                                    \App\Models\Order::STATUS_REGISTERED_ORIGIN => 'Au point de départ',
                                    \App\Models\Order::STATUS_IN_TRANSIT => 'En transit',
                                    \App\Models\Order::STATUS_ARRIVED_DESTINATION => 'Arrivée',
                                    \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION => 'Retrait requis',
                                    \App\Models\Order::STATUS_COMPLETED => 'Terminée',
                                    \App\Models\Order::STATUS_AUTO_COMPLETED => 'Terminée',
                                    \App\Models\Order::STATUS_DISPUTED => 'Litige',
                                    \App\Models\Order::STATUS_CANCELLED => 'Annulée',
                                    \App\Models\Order::STATUS_FAILED => 'Échec',
                                    default => ucfirst(str_replace('_', ' ', $order->status)),
                                };
                            @endphp

                            <tr class="group transition hover:bg-[#016837]/[0.025]">

                                <td class="whitespace-nowrap px-4 py-3">

                                    <span class="text-[10px] font-bold text-slate-800">
                                        {{ $order->reference }}
                                    </span>

                                </td>

                                <td class="max-w-[180px] px-4 py-3">

                                    <span class="block truncate text-[10px] font-medium text-slate-600">
                                        {{ $order->shop->name }}
                                    </span>

                                </td>

                                <td class="whitespace-nowrap px-4 py-3">

                                    <span class="text-[10px] font-extrabold text-slate-900">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }}
                                    </span>

                                    <span class="ml-0.5 text-[8px] font-bold text-[#F9A01B]">
                                        FCFA
                                    </span>

                                </td>

                                <td class="whitespace-nowrap px-4 py-3">

                                    <span class="inline-flex items-center rounded-full px-2 py-1
                                                 text-[8px] font-bold {{ $statusClass }}">

                                        <span class="mr-1 h-1.5 w-1.5 rounded-full bg-current"></span>

                                        {{ $statusLabel }}

                                    </span>

                                </td>

                                <td class="whitespace-nowrap px-4 py-3 text-[9px] text-slate-400">

                                    {{ $order->created_at->format('d/m/Y') }}

                                </td>

                                <td class="whitespace-nowrap px-4 py-3 text-right">

                                    <a href="{{ route('buyer.orders.show', $order) }}"
                                       class="inline-flex h-7 items-center gap-1 rounded-md px-2
                                              text-[9px] font-bold text-[#016837]
                                              transition hover:bg-[#016837]/10">

                                        Détails

                                        <svg class="h-3 w-3"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                VERSION MOBILE
            ================================================== --}}
            <div class="divide-y divide-slate-100 md:hidden">

                @foreach ($recentOrders as $order)

                    @php
                        $statusClass = match ($order->status) {
                            \App\Models\Order::STATUS_AWAITING_PAYMENT,
                            \App\Models\Order::STATUS_REGISTERED_ORIGIN,
                            \App\Models\Order::STATUS_ARRIVED_DESTINATION,
                            \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION =>
                                'bg-[#F9A01B]/15 text-[#946000]',

                            \App\Models\Order::STATUS_PAID,
                            \App\Models\Order::STATUS_PREPARING,
                            \App\Models\Order::STATUS_IN_TRANSIT,
                            \App\Models\Order::STATUS_COMPLETED,
                            \App\Models\Order::STATUS_AUTO_COMPLETED =>
                                'bg-[#016837]/10 text-[#016837]',

                            \App\Models\Order::STATUS_DISPUTED =>
                                'bg-[#E30613]/10 text-[#E30613]',

                            default =>
                                'bg-slate-100 text-slate-500',
                        };

                        $statusLabel = match ($order->status) {
                            \App\Models\Order::STATUS_PENDING => 'En attente',
                            \App\Models\Order::STATUS_AWAITING_PAYMENT => 'Paiement requis',
                            \App\Models\Order::STATUS_PAID => 'Payée',
                            \App\Models\Order::STATUS_PREPARING => 'Préparation',
                            \App\Models\Order::STATUS_REGISTERED_ORIGIN => 'Au départ',
                            \App\Models\Order::STATUS_IN_TRANSIT => 'En transit',
                            \App\Models\Order::STATUS_ARRIVED_DESTINATION => 'Arrivée',
                            \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION => 'Retrait requis',
                            \App\Models\Order::STATUS_COMPLETED,
                            \App\Models\Order::STATUS_AUTO_COMPLETED => 'Terminée',
                            \App\Models\Order::STATUS_DISPUTED => 'Litige',
                            \App\Models\Order::STATUS_CANCELLED => 'Annulée',
                            \App\Models\Order::STATUS_FAILED => 'Échec',
                            default => ucfirst(str_replace('_', ' ', $order->status)),
                        };
                    @endphp

                    <a href="{{ route('buyer.orders.show', $order) }}"
                       class="block p-3.5 transition hover:bg-slate-50">

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">

                                <div class="flex items-center gap-2">

                                    <span class="text-[10px] font-extrabold text-slate-800">
                                        {{ $order->reference }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full px-1.5 py-0.5
                                                 text-[7px] font-bold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                </div>

                                <p class="mt-1 truncate text-[9px] text-slate-500">
                                    {{ $order->shop->name }}
                                </p>

                                <p class="mt-1 text-[8px] text-slate-400">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </p>

                            </div>

                            <div class="shrink-0 text-right">

                                <p class="text-[10px] font-extrabold text-slate-900">
                                    {{ number_format($order->total_amount, 0, ',', ' ') }}
                                </p>

                                <p class="text-[8px] font-bold text-[#F9A01B]">
                                    FCFA
                                </p>

                                <svg class="ml-auto mt-1 h-3 w-3 text-slate-300"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div class="px-5 py-10 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-[#016837]/10 text-[#016837]">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                              d="M3 7h18M5 7l1.5 12h11L19 7M9 7V5a3 3 0 016 0v2"/>
                    </svg>

                </div>

                <h3 class="mt-3 text-sm font-extrabold text-slate-800">
                    Aucune commande pour le moment
                </h3>

                <p class="mx-auto mt-1 max-w-sm text-[10px] leading-relaxed text-slate-400">
                    Explorez les produits disponibles sur Ali-Kamer et effectuez
                    votre premier achat en toute simplicité.
                </p>

                <a href="{{ route('buyer.home') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-lg bg-[#016837] px-4 py-2
                          text-[10px] font-bold text-white shadow-sm transition
                          hover:bg-[#015a30]">

                    Découvrir les produits

                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>

                </a>

            </div>

        @endif

    </div>

</div>

@endsection