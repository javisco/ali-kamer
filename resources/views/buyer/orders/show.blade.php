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
                'pending'                     => ['label' => 'En attente',        'class' => 'bg-gray-100 text-gray-600'],
                'awaiting_payment'             => ['label' => 'Paiement requis',   'class' => 'bg-yellow-100 text-yellow-700'],
                'paid'                         => ['label' => 'Payé',              'class' => 'bg-blue-100 text-blue-700'],
                'preparing'                    => ['label' => 'En préparation',    'class' => 'bg-indigo-100 text-indigo-700'],
                'registered_origin'            => ['label' => 'Déposé en agence', 'class' => 'bg-indigo-100 text-indigo-700'],
                'in_transit'                   => ['label' => 'En transit',        'class' => 'bg-purple-100 text-purple-700'],
                'arrived_destination'          => ['label' => 'Arrivé',            'class' => 'bg-teal-100 text-teal-700'],
                'awaiting_buyer_confirmation'  => ['label' => 'À retirer',         'class' => 'bg-orange-100 text-orange-700'],
                'completed'                    => ['label' => 'Livré',             'class' => 'bg-emerald-100 text-emerald-700'],
                'auto_completed'               => ['label' => 'Livré (auto)',      'class' => 'bg-emerald-100 text-emerald-700'],
                'disputed'                     => ['label' => 'Litige',            'class' => 'bg-red-100 text-red-700'],
                'cancelled'                    => ['label' => 'Annulée',           'class' => 'bg-gray-100 text-gray-500'],
                'failed'                       => ['label' => 'Échouée',           'class' => 'bg-red-100 text-red-600'],
            ];
            $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
        @endphp
        <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $sc['class'] }}">
            {{ $sc['label'] }}
        </span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
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
            if (in_array($order->status, $step['status'])) $currentStep = $i;
        }
        if (in_array($order->status, ['completed', 'auto_completed'])) $currentStep = 4;
    @endphp

    @if(!in_array($order->status, ['cancelled', 'failed', 'disputed']))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
            <div class="flex items-center justify-between">
                @foreach($steps as $i => $step)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                    {{ $i <= $currentStep ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($i < $currentStep)
                                ✓
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <p class="text-xs mt-1 {{ $i <= $currentStep ? 'text-indigo-600 font-semibold' : 'text-gray-400' }}">
                            {{ $step['label'] }}
                        </p>
                    </div>
                    @if($i < count($steps) - 1)
                        <div class="flex-1 h-0.5 {{ $i < $currentStep ? 'bg-indigo-600' : 'bg-gray-200' }} -mt-4 mx-1"></div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Paiement requis --}}
    @if($order->status === 'awaiting_payment')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6">
            <h2 class="font-bold text-yellow-800 mb-2">⏳ Effectuez votre paiement</h2>
            <p class="text-sm text-yellow-700 mb-3">
                Transférez exactement <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong>
                vers le numéro MoMo de la plateforme.
            </p>
            <div class="bg-white rounded-xl border border-yellow-200 p-4 mb-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">MTN MoMo</span>
                    <span class="font-bold">6XX XXX XXX</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Orange Money</span>
                    <span class="font-bold">6XX XXX XXX</span>
                </div>
            </div>
            <p class="text-xs text-yellow-600">
                Après le transfert, conservez l'identifiant de transaction reçu par SMS.
                Un administrateur validera votre paiement sous quelques minutes.
            </p>
        </div>
    @endif

    {{-- OTP affiché si colis arrivé --}}
    @if($order->status === 'awaiting_buyer_confirmation' && $order->otp_code)
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 mb-6 text-center">
            <p class="text-sm font-semibold text-indigo-700 mb-2">
                🎉 Votre colis est arrivé ! Présentez ce code au secrétaire de l'agence.
            </p>
            <div class="text-4xl font-extrabold tracking-[0.3em] text-indigo-600 my-3">
                {{ $order->otp_code }}
            </div>
            @if($order->timer_deadline)
                <p class="text-xs text-indigo-500">
                    Valable jusqu'au {{ $order->timer_deadline->format('d/m/Y à H:i') }}
                </p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5">

        {{-- Articles --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Articles commandés</h2>
            @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 text-sm line-clamp-2">{{ $item->product_title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Qté : {{ $item->quantity }}</p>
                    </div>
                    <p class="font-bold text-gray-900 text-sm flex-shrink-0">
                        {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Récap financier --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-4">Récapitulatif financier</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Sous-total</span>
                    <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Protection (2%)</span>
                    <span>{{ number_format($order->protection_fee, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Frais Mobile Money (2%)</span>
                    <span>{{ number_format($order->gateway_fee, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-2 mt-2">
                    <span>Total payé</span>
                    <span class="text-indigo-600">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        {{-- Boutique --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-3">Boutique</h2>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold
                            flex items-center justify-center uppercase text-sm">
                    {{ substr($order->shop->name, 0, 2) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $order->shop->name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->shop->city }}</p>
                </div>
            </div>
        </div>

        {{-- Livraison --}}
        @if($order->shipment)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-3">Livraison</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Destinataire</span>
                        <span class="font-medium">{{ $order->shipment->recipient_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Téléphone</span>
                        <span class="font-medium">{{ $order->shipment->recipient_phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ville destination</span>
                        <span class="font-medium">{{ $order->shipment->destination_city }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Transport</span>
                        <span class="{{ $order->shipment->shipping_included ? 'text-emerald-600' : 'text-orange-500' }} font-medium">
                            {{ $order->shipment->shipping_included ? 'Inclus' : 'Exclu — payable à l\'arrivée' }}
                        </span>
                    </div>
                    @if($order->shipment->transport_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Frais transport</span>
                            <span class="font-bold text-orange-600">
                                {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col gap-3">
            @if(in_array($order->status, ['pending', 'awaiting_payment']))
                <form method="POST" action="{{ route('buyer.orders.cancel', $order) }}"
                      onsubmit="return confirm('Annuler cette commande ?')">
                    @csrf
                    <button class="w-full border-2 border-red-200 text-red-600 font-semibold
                                   py-3 rounded-2xl hover:bg-red-50 transition text-sm">
                        Annuler la commande
                    </button>
                </form>
            @endif

            @if($order->canBeDisputed())
                <a href="#"
                   class="block w-full border-2 border-orange-200 text-orange-600 font-semibold
                          py-3 rounded-2xl hover:bg-orange-50 transition text-sm text-center">
                    Signaler un problème
                </a>
            @endif

            <a href="{{ route('buyer.orders.index') }}"
               class="block w-full border-2 border-gray-200 text-gray-600 font-semibold
                      py-3 rounded-2xl hover:bg-gray-50 transition text-sm text-center">
                ← Mes commandes
            </a>
        </div>

    </div>
</div>
</div>
@endsection
