@extends('base')
@section('title', 'Payer les frais de transport')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Frais de transport</h1>
            <p class="text-sm text-gray-500 mb-6">Commande {{ $order->reference }}</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Info colis --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
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
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-5 text-center">
                <p class="text-sm text-orange-700 mb-1">Frais de transport à payer</p>
                <p class="text-4xl font-extrabold text-orange-600">
                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }}
                    <span class="text-lg font-semibold">FCFA</span>
                </p>
                <p class="text-xs text-orange-500 mt-2">
                    Ces frais seront remboursés au vendeur qui les a avancés.
                </p>
            </div>

            {{-- Formulaire paiement --}}
            <form method="POST" action="{{ route('buyer.orders.transport.pay', $order) }}"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-5">
                @csrf

                {{-- Opérateur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Opérateur Mobile Money <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3">
                        <label
                            class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                              {{ old('transport_operator', $order->payment->payer_operator) === 'mtn'
                                  ? 'border-yellow-400 bg-yellow-50'
                                  : 'border-gray-200' }}">
                            <input type="radio" name="transport_operator" value="mtn"
                                {{ old('transport_operator', $order->payment->payer_operator) === 'mtn' ? 'checked' : '' }}
                                required>
                            <span class="font-semibold text-yellow-700 text-sm">MTN MoMo</span>
                        </label>
                        <label
                            class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                              {{ old('transport_operator', $order->payment->payer_operator) === 'orange'
                                  ? 'border-orange-400 bg-orange-50'
                                  : 'border-gray-200' }}">
                            <input type="radio" name="transport_operator" value="orange"
                                {{ old('transport_operator', $order->payment->payer_operator) === 'orange' ? 'checked' : '' }}>
                            <span class="font-semibold text-orange-600 text-sm">Orange Money</span>
                        </label>
                    </div>
                    @error('transport_operator')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro MoMo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Numéro Mobile Money <span class="text-red-500">*</span>
                    </label>
                    {{-- Pré-rempli avec le numéro de la commande originale
                 mais l'acheteur peut en saisir un autre --}}
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 border border-r-0 border-gray-300
                             rounded-l-xl bg-gray-50 text-gray-500 text-sm">+237</span>
                        <input type="tel" name="transport_phone"
                            value="{{ old('transport_phone', $order->payment->payer_phone) }}" required
                            placeholder="655123456"
                            class="flex-1 border border-gray-300 rounded-r-xl px-4 py-2.5 text-sm
                              focus:ring-2 focus:ring-orange-500">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        Vous pouvez utiliser un numéro différent de celui de la commande.
                    </p>
                    @error('transport_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
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
