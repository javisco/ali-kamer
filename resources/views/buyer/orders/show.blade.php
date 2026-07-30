@extends('base')

@section('title', 'Commande ' . $order->reference)

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ $order->reference }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                {{-- Badge statut --}}
                @php
                    $statusConfig = [
                        'pending' => ['label' => 'En attente', 'class' => 'bg-gray-100 text-gray-600'],
                        'awaiting_payment' => ['label' => 'Paiement requis', 'class' => 'bg-amber-100 text-amber-800'],
                        'paid' => ['label' => 'Payé', 'class' => 'bg-blue-100 text-blue-700'],
                        'preparing' => ['label' => 'En préparation', 'class' => 'bg-indigo-100 text-indigo-700'],
                        'registered_origin' => [
                            'label' => 'Déposé en agence',
                            'class' => 'bg-indigo-100 text-indigo-700',
                        ],
                        'in_transit' => ['label' => 'En transit', 'class' => 'bg-purple-100 text-purple-700'],
                        'arrived_destination' => ['label' => 'Arrivé', 'class' => 'bg-teal-100 text-teal-700'],
                        'awaiting_buyer_confirmation' => [
                            'label' => 'À retirer',
                            'class' => 'bg-orange-100 text-orange-700',
                        ],
                        'completed' => ['label' => 'Livré', 'class' => 'bg-emerald-100 text-emerald-700'],
                        'auto_completed' => ['label' => 'Livré (auto)', 'class' => 'bg-emerald-100 text-emerald-700'],
                        'disputed' => ['label' => 'Litige', 'class' => 'bg-red-100 text-red-700'],
                        'cancelled' => ['label' => 'Annulée', 'class' => 'bg-gray-100 text-gray-500'],
                        'failed' => ['label' => 'Échouée', 'class' => 'bg-red-100 text-red-600'],
                    ];
                    $sc = $statusConfig[$order->status] ?? [
                        'label' => $order->status,
                        'class' => 'bg-gray-100 text-gray-600',
                    ];
                @endphp
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $sc['class'] }}">
                    {{ $sc['label'] }}
                </span>
            </div>

            @if (session('success'))
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Étapes de suivi --}}
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
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                    <div class="flex items-center justify-between">
                        @foreach ($steps as $i => $step)
                            <div class="flex flex-col items-center flex-1">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all
                                        {{ $i <= $currentStep ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400' }}">
                                    @if ($i < $currentStep)
                                        ✓
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </div>
                                <p
                                    class="text-[11px] mt-2 text-center {{ $i <= $currentStep ? 'text-indigo-600 font-bold' : 'text-gray-400' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                            @if ($i < count($steps) - 1)
                                <div
                                    class="flex-1 h-0.5 {{ $i < $currentStep ? 'bg-indigo-600' : 'bg-gray-100' }} -mt-5 mx-1">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bloc d'instruction si le paiement est requis --}}
            @if ($order->status === 'awaiting_payment')
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-amber-900 mb-1">⏳ En attente de paiement</h2>
                            <p class="text-sm text-amber-800 mb-3">
                                Transférez exactement <strong
                                    class="font-extrabold">{{ number_format($order->total_amount, 0, ',', ' ') }}
                                    FCFA</strong>
                                vers le numéro de la plateforme ci-dessous :
                            </p>
                            <div class="bg-white rounded-xl border border-amber-200 p-4 space-y-2 text-sm shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">MTN MoMo</span>
                                    <span
                                        class="font-bold font-mono bg-yellow-50 text-yellow-800 px-2.5 py-1 rounded-lg border border-yellow-200">6XX
                                        XXX XXX</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                                    <span class="text-gray-600 font-medium">Orange Money</span>
                                    <span
                                        class="font-bold font-mono bg-orange-50 text-orange-800 px-2.5 py-1 rounded-lg border border-orange-200">6XX
                                        XXX XXX</span>
                                </div>
                            </div>
                            <p class="text-xs text-amber-700 mt-3">
                                Conservez l'ID de la transaction reçu par SMS après transfert. L'administrateur validera
                                votre paiement.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Code OTP si colis arrivé --}}
            @if ($order->status === 'awaiting_buyer_confirmation' && $order->otp_code)
                <div class="bg-indigo-600 rounded-2xl p-6 mb-6 text-center text-white shadow-lg shadow-indigo-100">
                    <p class="text-sm font-medium text-indigo-100 mb-1">
                        🎉 Votre colis est arrivé en agence !
                    </p>
                    <p class="text-xs text-indigo-200 mb-3">Présentez ce code de retrait au secrétaire</p>

                    <div class="bg-white/10 backdrop-blur-md rounded-xl py-3 px-6 inline-block border border-white/20 my-1">
                        <span class="text-4xl font-black tracking-[0.3em] font-mono text-white">
                            {{ $order->otp_code }}
                        </span>
                    </div>

                    @if ($order->timer_deadline)
                        <p class="text-xs text-indigo-200 mt-3">
                            Valable jusqu'au {{ $order->timer_deadline->format('d/m/Y à H:i') }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="space-y-5">

                {{-- Articles --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4 text-xs uppercase tracking-wider text-gray-400">Articles
                        commandés</h2>
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm line-clamp-2">{{ $item->product_title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA × {{ $item->quantity }}
                                </p>
                            </div>
                            <p class="font-bold text-gray-900 text-sm flex-shrink-0">
                                {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Mode de Paiement (OrderPayment) --}}
                @if ($order->payment)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3">Informations de règlement
                        </h2>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Opérateur</span>
                                <span
                                    class="font-bold uppercase text-xs px-2.5 py-1 rounded-md border
                                {{ $order->payment->payer_operator === 'mtn' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : 'bg-orange-50 border-orange-200 text-orange-800' }}">
                                    {{ $order->payment->payer_operator }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Numéro payeur</span>
                                <span class="font-medium text-gray-900">+237 {{ $order->payment->payer_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Méthode</span>
                                <span class="font-medium text-gray-800 capitalize">{{ $order->payment->method }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-50 pt-2">
                                <span class="text-gray-500">Statut du règlement</span>
                                <span
                                    class="text-xs font-semibold px-2 py-0.5 rounded
                                {{ $order->payment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Livraison (OrderShipment) --}}
                @if ($order->shipment)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3">Expédition & Livraison
                        </h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Destinataire</span>
                                <span class="font-semibold text-gray-900">{{ $order->shipment->recipient_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Téléphone contact</span>
                                <span class="font-medium text-gray-800">{{ $order->shipment->recipient_phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ville de destination</span>
                                <span class="font-medium text-gray-800">{{ $order->shipment->destination_city }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-gray-500">Frais d'expédition</span>
                                <span
                                    class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $order->shipment->shipping_included ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-orange-50 text-orange-700 border border-orange-100' }}">
                                    {{ $order->shipment->shipping_included ? '✓ Inclus dans le prix' : '⚠ Exclus — Payables à l\'arrivée' }}
                                </span>
                            </div>
                            @if ($order->shipment->transport_fee > 0)
                                <div class="flex justify-between items-center border-t border-gray-50 pt-2 mt-2">
                                    <span class="text-gray-500">Montant transport</span>
                                    <span class="font-bold text-orange-600">
                                        {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Boutique --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-xs uppercase tracking-wider text-gray-400 mb-3">Vendeur</h2>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold
                                flex items-center justify-center uppercase text-sm border border-indigo-100">
                            {{ substr($order->shop->name, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $order->shop->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->shop->city }}</p>
                        </div>
                    </div>
                </div>

                {{-- Récapitulatif financier --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5">
                    <h2 class="font-bold text-gray-900 mb-3">Récapitulatif Financier</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Sous-total articles</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->subtotal, 0, ',', ' ') }}
                                FCFA</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Frais de protection (2%)</span>
                            <span
                                class="font-medium text-gray-900">{{ number_format($order->protection_fee, 0, ',', ' ') }}
                                FCFA</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Frais Mobile Money (2%)</span>
                            <span class="font-medium text-gray-900">{{ number_format($order->gateway_fee, 0, ',', ' ') }}
                                FCFA</span>
                        </div>
                        <div
                            class="flex justify-between font-extrabold text-gray-900 border-t border-indigo-200 pt-3 mt-2 text-base">
                            <span>Total général</span>
                            <span class="text-indigo-600">{{ number_format($order->total_amount, 0, ',', ' ') }}
                                FCFA</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3 pt-2">
                    @if (in_array($order->status, ['pending', 'awaiting_payment']))
                        <form method="POST" action="{{ route('buyer.orders.cancel', $order) }}"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            @csrf
                            <button
                                class="w-full border-2 border-red-200 text-red-600 font-semibold
                                       py-3 rounded-2xl hover:bg-red-50 transition text-sm">
                                Annuler la commande
                            </button>
                        </form>
                    @endif

                    @if ($order->canBeDisputed())
                        <a href="#"
                            class="block w-full border-2 border-orange-200 text-orange-600 font-semibold
                              py-3 rounded-2xl hover:bg-orange-50 transition text-sm text-center">
                            Signaler un problème / Ouvrir un litige
                        </a>
                    @endif

                    <a href="{{ route('buyer.orders.index') }}"
                        class="block w-full bg-white border border-gray-200 text-gray-600 font-semibold
                          py-3 rounded-2xl hover:bg-gray-50 transition text-sm text-center shadow-sm">
                        ← Retour à mes commandes
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
