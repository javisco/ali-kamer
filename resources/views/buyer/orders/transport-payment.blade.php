@extends('base')
@section('title', 'Payer les frais de transport')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Frais de transport</h1>
            <p class="text-sm text-gray-500 mb-6">Commande {{ $order->reference }}</p>

            {{-- Info colis --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-3">Votre colis est arrivé !</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Agence</span>
                        <span class="font-medium">
                            {{ $order->shipment->destinationCounter->full_name ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ville</span>
                        <span class="font-medium">{{ $order->shipment->destination_city }}</span>
                    </div>
                </div>
            </div>

            {{-- Montant --}}
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-6 text-center">
                <p class="text-sm text-orange-700 mb-1">Frais de transport à payer</p>
                <p class="text-4xl font-extrabold text-orange-600">
                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                    <span class="text-lg font-semibold">FCFA</span>
                </p>
                <p class="text-xs text-orange-500 mt-2">
                    Ces frais seront remboursés au vendeur qui les a avancés.
                </p>
            </div>

            {{-- Numéro MoMo --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <p class="text-sm font-medium text-gray-700 mb-2">Paiement depuis</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Numéro</span>
                    <span class="font-semibold">+237 {{ $order->payment->payer_phone }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-gray-500">Opérateur</span>
                    <span class="font-semibold uppercase">{{ $order->payment->payer_operator }}</span>
                </div>
            </div>

            {{-- Bouton paiement --}}
            <form method="POST" action="{{ route('buyer.orders.transport.pay', $order) }}">
                @csrf
                <button
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold
                       py-4 rounded-2xl transition text-base">
                    Payer {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                </button>
            </form>

            <p class="text-xs text-center text-gray-400 mt-4">
                Vous recevrez une notification sur votre téléphone pour confirmer.
                Le colis ne sera remis qu'après confirmation du paiement.
            </p>

        </div>
    </div>
@endsection
