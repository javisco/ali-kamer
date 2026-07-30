@extends('base')

@section('title', 'Paiement — ' . $order->reference)

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Paiement Mobile Money</h1>
            <p class="text-sm text-gray-500 mb-6">Commande {{ $order->reference }}</p>

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Récapitulatif --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-3">Récapitulatif</h2>
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
                    <div class="flex justify-between font-bold text-gray-900 border-t pt-2 mt-2">
                        <span>Total à payer</span>
                        <span class="text-indigo-600 text-lg">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>

            {{-- Infos MoMo --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-3">Votre numéro de paiement</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Opérateur</span>
                        <span class="font-semibold uppercase">
                            {{ $order->payment->payer_operator }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Numéro</span>
                        <span class="font-semibold">+237 {{ $order->payment->payer_phone }}</span>
                    </div>
                </div>
            </div>

            {{-- Bouton paiement --}}
            <form method="POST" action="{{ route('buyer.payment.initiate', $order) }}">
                @csrf
                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                    py-4 rounded-2xl transition text-base mb-3">
                    Payer {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                </button>
            </form>

            <p class="text-xs text-center text-gray-400">
                Vous recevrez un message sur votre téléphone pour confirmer le paiement.
                Gardez votre téléphone à portée de main.
            </p>

        </div>
    </div>
@endsection
