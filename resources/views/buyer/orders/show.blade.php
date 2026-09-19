@extends('layouts.buyer')

@section('title', 'Commande ' . $order->reference)

@section('content')

<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- =========================================================
            EN-TÊTE
        ========================================================== --}}
        <div class="flex items-start justify-between gap-4 mb-6">

            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-accent-500">
                        Détails de la commande
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 truncate">
                    {{ $order->reference }}
                </h1>

                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            {{-- Badge statut --}}
            @php
                $statusConfig = [
                    'pending' => ['label' => 'En attente', 'class' => 'bg-slate-100 text-slate-600'],
                    'awaiting_payment' => ['label' => 'Paiement requis', 'class' => 'bg-accent-500/15 text-accent-600'],
                    'paid' => ['label' => 'Payé', 'class' => 'bg-primary-600/10 text-primary-600'],
                    'preparing' => ['label' => 'En préparation', 'class' => 'bg-primary-600/10 text-primary-600'],
                    'registered_origin' => [
                        'label' => 'Déposé en agence',
                        'class' => 'bg-primary-600/10 text-primary-600',
                    ],
                    'in_transit' => ['label' => 'En transit', 'class' => 'bg-accent-500/15 text-accent-600'],
                    'arrived_destination' => ['label' => 'Arrivé', 'class' => 'bg-primary-600/10 text-primary-600'],
                    'awaiting_buyer_confirmation' => [
                        'label' => 'À retirer',
                        'class' => 'bg-accent-500/15 text-accent-600',
                    ],
                    'completed' => ['label' => 'Livré', 'class' => 'bg-primary-600/10 text-primary-600'],
                    'auto_completed' => ['label' => 'Livré (auto)', 'class' => 'bg-primary-600/10 text-primary-600'],
                    'disputed' => ['label' => 'Litige', 'class' => 'bg-danger/10 text-danger'],
                    'cancelled' => ['label' => 'Annulée', 'class' => 'bg-slate-100 text-slate-500'],
                    'failed' => ['label' => 'Échouée', 'class' => 'bg-danger/10 text-danger'],
                ];

                $sc = $statusConfig[$order->status] ?? [
                    'label' => $order->status,
                    'class' => 'bg-slate-100 text-slate-600',
                ];
            @endphp

            <span class="flex-shrink-0 px-3 py-1.5 rounded-full text-[10px] sm:text-xs
                         font-extrabold uppercase tracking-wider {{ $sc['class'] }}">
                {{ $sc['label'] }}
            </span>

        </div>


        {{-- =========================================================
            MESSAGE SUCCÈS
        ========================================================== --}}
        @if (session('success'))
            <div class="bg-primary-600/10 border border-primary-600/20 text-primary-600
                        px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">

                <div class="w-6 h-6 rounded-lg bg-primary-600 text-white
                            flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <span>{{ session('success') }}</span>
            </div>
        @endif


        {{-- =========================================================
            ÉTAPES DE SUIVI
        ========================================================== --}}
        @php
            $steps = [
                ['status' => ['paid'], 'label' => 'Payé'],
                ['status' => ['preparing'], 'label' => 'En préparation'],
                ['status' => ['registered_origin', 'in_transit'], 'label' => 'En route'],
                ['status' => ['arrived_destination', 'awaiting_buyer_confirmation'], 'label' => 'Arrivé'],
                ['status' => ['completed', 'auto_completed'], 'label' => 'Livré'],
            ];

            $currentStep = 0;

            foreach ($steps as $i => $step) {
                if (in_array($order->status, $step['status'])) {
                    $currentStep = $i;
                }
            }

            if (in_array($order->status, ['completed', 'auto_completed'])) {
                $currentStep = 4;
            }
        @endphp

        @if (!in_array($order->status, ['cancelled', 'failed', 'disputed']))

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm
                        p-4 sm:p-5 mb-5">

                <div class="flex items-center justify-between">

                    @foreach ($steps as $i => $step)

                        <div class="flex flex-col items-center flex-1 min-w-0">

                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        text-xs font-extrabold transition-all
                                {{ $i <= $currentStep
                                    ? 'bg-primary-600 text-white shadow-sm'
                                    : 'bg-slate-100 text-slate-400' }}">

                                @if ($i < $currentStep)
                                    ✓
                                @else
                                    {{ $i + 1 }}
                                @endif

                            </div>

                            <p class="text-[9px] sm:text-[11px] mt-2 text-center leading-tight
                                {{ $i <= $currentStep
                                    ? 'text-primary-600 font-bold'
                                    : 'text-slate-400' }}">
                                {{ $step['label'] }}
                            </p>

                        </div>

                        @if ($i < count($steps) - 1)

                            <div class="flex-1 h-0.5
                                {{ $i < $currentStep
                                    ? 'bg-primary-600'
                                    : 'bg-slate-100' }}
                                -mt-5 mx-1">
                            </div>

                        @endif

                    @endforeach

                </div>
            </div>

        @endif


        {{-- =========================================================
            NOTER LA COMMANDE
        ========================================================== --}}
        @if ($order->isCompleted())

            <a href="{{ route('buyer.reviews.create', $order) }}"
               class="flex items-center justify-center gap-2 w-full
                      bg-accent-500 hover:bg-accent-500
                      text-slate-900 font-extrabold
                      py-3 rounded-2xl text-center text-sm
                      transition mb-4">

                <span class="text-lg">★</span>
                Noter cette commande

            </a>

        @endif


        {{-- =========================================================
            PAIEMENT REQUIS
        ========================================================== --}}
        @if ($order->status === 'awaiting_payment')

            <div class="bg-accent-500/10 border border-accent-500/25
                        rounded-2xl p-4 sm:p-5 mb-5">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-accent-500/20
                                text-accent-600 flex items-center justify-center
                                flex-shrink-0">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    </div>

                    <div class="flex-1">

                        <h2 class="font-extrabold text-slate-900 mb-1">
                            ⏳ En attente de paiement
                        </h2>

                        <p class="text-sm text-accent-700 mb-3">
                            Transférez exactement
                            <strong class="font-extrabold text-slate-900">
                                {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                            </strong>
                            vers le numéro de la plateforme ci-dessous :
                        </p>

                        <div class="bg-white rounded-xl border border-accent-500/20
                                    p-3.5 space-y-2 text-sm shadow-sm">

                            <div class="flex justify-between items-center gap-3">
                                <span class="text-slate-600 font-medium">
                                    MTN MoMo
                                </span>

                                <span class="font-bold font-mono
                                             bg-accent-500/10 text-accent-600
                                             px-2.5 py-1 rounded-lg
                                             border border-accent-500/20">
                                    6XX XXX XXX
                                </span>
                            </div>

                            <div class="flex justify-between items-center gap-3
                                        border-t border-slate-100 pt-2">

                                <span class="text-slate-600 font-medium">
                                    Orange Money
                                </span>

                                <span class="font-bold font-mono
                                             bg-danger/5 text-danger
                                             px-2.5 py-1 rounded-lg
                                             border border-danger/15">
                                    6XX XXX XXX
                                </span>

                            </div>

                        </div>

                        <p class="text-xs text-accent-600 mt-3">
                            Conservez l'ID de la transaction reçu par SMS après transfert.
                            L'administrateur validera votre paiement.
                        </p>

                    </div>

                </div>
            </div>

        @endif


        {{-- =========================================================
            CODE OTP
        ========================================================== --}}
        @if (
            $order->otp_code &&
                in_array($order->status, [
                    \App\Models\Order::STATUS_REGISTERED_ORIGIN,
                    \App\Models\Order::STATUS_IN_TRANSIT,
                    \App\Models\Order::STATUS_ARRIVED_DESTINATION,
                    \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION,
                ]))

            <div class="mt-5 rounded-2xl border border-primary-600/20
                        bg-primary-600/5 p-4 sm:p-5">

                <div class="flex items-start gap-3 sm:gap-4">

                    <div class="w-11 h-11 rounded-xl bg-primary-600 text-white
                                flex items-center justify-center flex-shrink-0">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 15v2m-4-6V9a4 4 0 118 0v2m-7
                                     0h6a2 2 0 012 2v5a2 2 0 01-2 2H7a2
                                     2 0 01-2-2v-5a2 2 0 012-2z"/>
                        </svg>

                    </div>

                    <div class="flex-1">

                        <h3 class="font-extrabold text-slate-900">
                            Votre code de retrait
                        </h3>

                        <p class="text-sm text-primary-600 mt-1">
                            Présentez ce code au secrétaire lors du retrait
                            de votre colis.
                        </p>

                        <div class="mt-4 inline-flex items-center px-5 py-3
                                    bg-white border border-primary-600/20
                                    rounded-xl shadow-sm">

                            <span class="text-2xl sm:text-3xl font-black
                                         tracking-[0.3em] text-primary-600">
                                {{ $order->otp_code }}
                            </span>

                        </div>

                        @if ($order->otp_expires_at)

                            <p class="text-xs text-primary-600 mt-3">
                                Valable jusqu'au
                                {{ $order->otp_expires_at->format('d/m/Y à H:i') }}
                            </p>

                        @endif

                    </div>

                </div>
            </div>

        @endif


        {{-- =========================================================
            NOTE REÇUE DU VENDEUR
        ========================================================== --}}
        @if ($order->isCompleted())

            @php
                $buyerReview = \App\Models\Review::where('order_id', $order->id)
                    ->where('reviewee_type', 'buyer')
                    ->where('reviewee_id', auth()->id())
                    ->first();
            @endphp

            @if ($buyerReview)

                <div class="bg-white rounded-2xl border border-slate-100
                            shadow-sm p-5 mb-5">

                    <div class="flex items-center gap-2 mb-3">

                        <div class="w-8 h-8 rounded-lg bg-accent-500/10
                                    flex items-center justify-center">

                            <span class="text-accent-500">★</span>

                        </div>

                        <h2 class="font-bold text-slate-900">
                            Votre note reçue du vendeur
                        </h2>

                    </div>

                    <div class="flex items-center gap-2">

                        <span class="text-accent-500 text-xl">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= $buyerReview->rating ? '★' : '☆' }}
                            @endfor
                        </span>

                        <span class="text-lg font-extrabold text-slate-900">
                            {{ $buyerReview->rating }}/5
                        </span>

                    </div>

                    @if ($buyerReview->body)

                        <p class="text-sm text-slate-600 mt-2 italic">
                            "{{ $buyerReview->body }}"
                        </p>

                    @endif

                    <p class="text-xs text-slate-400 mt-2">
                        Score de fiabilité actuel :
                        <strong class="text-primary-600">
                            {{ auth()->user()->trust_score }}/100
                        </strong>
                    </p>

                </div>

            @endif

        @endif


        <div class="space-y-4">


            {{-- =====================================================
                ARTICLES
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-100
                        shadow-sm p-5">

                <h2 class="font-extrabold text-slate-900 mb-4 text-[11px]
                           uppercase tracking-wider flex items-center gap-2">

                    <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                    Articles commandés

                </h2>

                @foreach ($order->items as $item)

                    <div class="flex items-center gap-3.5 py-3
                                {{ !$loop->last ? 'border-b border-slate-100' : '' }}">

                        {{-- Visuel Produit / Variante --}}
                        @php
                            $itemImage = ($item->variant && $item->variant->images?->isNotEmpty())
                                ? $item->variant->images->first()->url
                                : $item->product?->images->first()?->url;
                        @endphp
                        @if ($itemImage)
                            <img src="{{ asset('storage/' . $itemImage) }}"
                                 alt="{{ $item->product_title }}"
                                 class="w-14 h-14 rounded-xl object-cover flex-shrink-0 bg-slate-50 border border-slate-100 shadow-xs">
                        @else
                            <div class="w-14 h-14 rounded-xl bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">

                            <p class="font-bold text-slate-900 text-sm line-clamp-1">
                                {{ $item->product_title }}
                            </p>

                            {{-- Spécifications exactes de la variante --}}
                            @if (!empty($item->variant_snapshot['attributes']))
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @foreach ($item->variant_snapshot['attributes'] as $attr)
                                        <span class="inline-flex items-center text-[11px] font-semibold text-slate-700 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md">
                                            <span class="text-slate-400 font-medium mr-1">{{ $attr['name'] }}:</span> {{ $attr['value'] }}
                                        </span>
                                    @endforeach
                                    @if (!empty($item->variant_snapshot['sku']))
                                        <span class="inline-flex items-center text-[10px] font-mono text-slate-500 bg-slate-50 border border-slate-200/60 px-1.5 py-0.5 rounded">
                                            SKU: {{ $item->variant_snapshot['sku'] }}
                                        </span>
                                    @endif
                                </div>
                            @elseif ($item->variant_label)
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="inline-flex items-center text-[11px] font-semibold text-slate-700 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md">
                                        {{ str_replace(' / ', ' • ', $item->variant_label) }}
                                    </span>
                                </div>
                            @endif

                            <p class="text-xs text-slate-400 mt-1 font-medium">
                                {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA × {{ $item->quantity }}
                            </p>

                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="font-extrabold text-primary-600 text-sm sm:text-base">
                                {{ number_format($item->subtotal, 0, ',', ' ') }} <span class="text-[10px] font-bold text-slate-500">FCFA</span>
                            </p>
                        </div>

                    </div>

                @endforeach

            </div>


            {{-- =====================================================
                MODE DE PAIEMENT
            ====================================================== --}}
            @if ($order->payment)

                <div class="bg-white rounded-2xl border border-slate-100
                            shadow-sm p-5">

                    <h2 class="font-extrabold text-[11px] uppercase
                               tracking-wider text-slate-400 mb-3">
                        Informations de règlement
                    </h2>

                    <div class="space-y-2.5 text-sm">

                        <div class="flex justify-between items-center gap-3">
                            <span class="text-slate-500">
                                Opérateur
                            </span>

                            <span class="font-bold uppercase text-[10px]
                                         px-2.5 py-1 rounded-md border
                                {{ $order->payment->payer_operator === 'mtn'
                                    ? 'bg-accent-500/10 border-accent-500/20 text-accent-600'
                                    : 'bg-danger/5 border-danger/15 text-danger' }}">
                                {{ $order->payment->payer_operator }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">
                                Numéro payeur
                            </span>

                            <span class="font-medium text-slate-900 text-right">
                                +237 {{ $order->payment->payer_phone }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">
                                Méthode
                            </span>

                            <span class="font-medium text-slate-800 capitalize">
                                {{ $order->payment->method }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center gap-3
                                    border-t border-slate-50 pt-2">

                            <span class="text-slate-500">
                                Statut du règlement
                            </span>

                            <span class="text-[10px] font-bold px-2 py-1 rounded
                                {{ $order->payment->status === 'completed'
                                    ? 'bg-primary-600/10 text-primary-600'
                                    : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>

                        </div>

                    </div>
                </div>

            @endif


            {{-- =====================================================
                FRAIS DE TRANSPORT
            ====================================================== --}}
            @if (
                $order->shipment &&
                    !$order->shipment->shipping_included &&
                    $order->shipment->transport_fee > 0 &&
                    !$order->shipment->transport_fee_paid &&
                    $order->status === 'awaiting_buyer_confirmation'
            )

                <div class="bg-danger/5 border border-danger/20
                            rounded-2xl p-4">

                    <p class="text-sm font-extrabold text-danger mb-1">
                        ⚠ Frais de transport à payer avant retrait
                    </p>

                    <p class="text-sm text-danger/80 mb-3">
                        Montant :
                        {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                        FCFA
                    </p>

                    <a href="{{ route('buyer.orders.transport', $order) }}"
                       class="block w-full bg-accent-600 hover:bg-accent-700
                              text-white font-extrabold
                              py-3 rounded-xl text-center text-sm transition">

                        Payer les frais de transport

                    </a>

                </div>

            @endif


            {{-- =====================================================
                EXPÉDITION & LIVRAISON
            ====================================================== --}}
            @if ($order->shipment)

                <div class="bg-white rounded-2xl border border-slate-100
                            shadow-sm p-5">

                    <h2 class="font-extrabold text-[11px] uppercase
                               tracking-wider text-slate-400 mb-3">
                        Expédition & Livraison
                    </h2>

                    <div class="space-y-2.5 text-sm">

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Destinataire</span>
                            <span class="font-semibold text-slate-900 text-right">
                                {{ $order->shipment->recipient_name }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Téléphone contact</span>
                            <span class="font-medium text-slate-800 text-right">
                                {{ $order->shipment->recipient_phone }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Ville de destination</span>
                            <span class="font-medium text-slate-800 text-right">
                                {{ $order->shipment->destination_city }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center gap-3 pt-1">

                            <span class="text-slate-500">
                                Frais d'expédition
                            </span>

                            <span class="text-[10px] sm:text-xs font-bold
                                        px-2.5 py-1 rounded-lg text-right
                                {{ $order->shipment->shipping_included
                                    ? 'bg-primary-600/10 text-primary-600 border border-primary-600/15'
                                    : 'bg-accent-500/10 text-accent-600 border border-accent-500/20' }}">

                                {{ $order->shipment->shipping_included
                                    ? '✓ Inclus dans le prix'
                                    : '⚠ Exclus — Payables à l\'arrivée' }}

                            </span>

                        </div>

                        @if ($order->shipment?->transport_fee > 0)

                            <div class="flex justify-between items-center
                                        border-t border-slate-50 pt-2 mt-2">

                                <span class="text-slate-500">
                                    Montant transport
                                </span>

                                <span class="font-extrabold text-danger">
                                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                                    FCFA
                                </span>

                            </div>

                        @endif

                    </div>
                </div>

            @endif


            {{-- =====================================================
                BOUTIQUE
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-100
                        shadow-sm p-5">

                <h2 class="font-extrabold text-[11px] uppercase
                           tracking-wider text-slate-400 mb-3">
                    Vendeur
                </h2>

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-primary-600/10
                                text-primary-600 font-extrabold
                                flex items-center justify-center uppercase
                                text-sm border border-primary-600/10">

                        {{ substr($order->shop->name, 0, 2) }}

                    </div>

                    <div>
                        <p class="font-bold text-slate-900 text-sm">
                            {{ $order->shop->name }}
                        </p>

                        <p class="text-xs text-slate-400">
                            {{ $order->shop->city }}
                        </p>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                RÉCAPITULATIF FINANCIER
            ====================================================== --}}
            <div class="bg-primary-600/5 border border-primary-600/15
                        rounded-2xl p-5">

                <div class="flex items-center gap-2 mb-4">

                    <div class="w-8 h-8 rounded-lg bg-primary-600
                                text-white flex items-center justify-center">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343
                                     2 3 2 3 .895 3 2-1.343 2-3
                                     2m0-8c1.11 0 2.08.402 2.599
                                     1M12 8V7m0 1v8m0 0v1m0-1c-1.11
                                     0-2.08-.402-2.599-1M21 12a9
                                     9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    </div>

                    <h2 class="font-extrabold text-slate-900">
                        Récapitulatif financier
                    </h2>

                </div>

                <div class="space-y-2.5 text-sm">

                    <div class="flex justify-between text-slate-600">
                        <span>Sous-total articles</span>

                        <span class="font-medium text-slate-900">
                            {{ number_format($order->subtotal, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>Frais de protection (2%)</span>

                        <span class="font-medium text-slate-900">
                            {{ number_format($order->protection_fee, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>Frais Mobile Money (2%)</span>

                        <span class="font-medium text-slate-900">
                            {{ number_format($order->gateway_fee, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="flex justify-between font-extrabold
                                text-slate-900 border-t border-primary-600/15
                                pt-3 mt-3 text-base">

                        <span>Total général</span>

                        <span class="text-primary-600">
                            {{ number_format($order->total_amount, 0, ',', ' ') }}
                            FCFA
                        </span>

                    </div>

                </div>
            </div>


            {{-- =====================================================
                ACTIONS
            ====================================================== --}}
            <div class="flex flex-col gap-3 pt-1">

                @if (in_array($order->status, ['pending', 'awaiting_payment']))

                    <form method="POST"
                          action="{{ route('buyer.orders.cancel', $order) }}">

                        @csrf

                        <button
                            class="w-full border-2 border-danger/20
                                   text-danger font-bold
                                   py-3 rounded-2xl
                                   hover:bg-danger/5 transition text-sm">

                            Annuler la commande

                        </button>

                    </form>

                @endif


                @if ($order->canBeDisputed())

                    @if ($order->dispute)

                        <a href="{{ route('buyer.disputes.show', $order->dispute) }}"
                           class="block w-full bg-danger/5
                                  border-2 border-danger/20
                                  text-danger font-bold
                                  py-3 rounded-2xl
                                  hover:bg-danger/10
                                  transition text-sm text-center">

                            Voir le litige

                        </a>

                    @else

                        <a href="{{ route('buyer.disputes.create', $order) }}"
                           class="block w-full bg-accent-500/10
                                  border-2 border-accent-500/25
                                  text-accent-600 font-bold
                                  py-3 rounded-2xl
                                  hover:bg-accent-500/20
                                  transition text-sm text-center">

                            ⚠️ Signaler un problème / Ouvrir un litige

                        </a>

                    @endif

                @endif


                <a href="{{ route('buyer.orders.index') }}"
                   class="block w-full bg-white
                          border border-slate-200
                          text-slate-600 font-bold
                          py-3 rounded-2xl
                          hover:bg-slate-50
                          transition text-sm text-center shadow-sm">

                    ← Retour à mes commandes

                </a>

            </div>

        </div>
    </div>
</div>

@endsection