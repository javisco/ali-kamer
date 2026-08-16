@extends('base')
@section('title', 'Colis trouvé')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-lg mx-auto px-4">

            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('secretary.deposit.page') }}" class="text-gray-400 hover:text-gray-600">←</a>
                <h1 class="text-2xl font-extrabold text-gray-900">Colis trouvé</h1>
            </div>

            {{-- Infos commande --}}
            <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900">{{ $order->reference }}</h2>
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        En préparation
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vendeur</span>
                        <span class="font-medium">{{ $order->shop->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Destinataire</span>
                        <span class="font-medium">{{ $order->shipment->recipient_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Téléphone destinataire</span>
                        <span class="font-medium">{{ $order->shipment->recipient_phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ville destination</span>
                        <span class="font-bold text-indigo-600">{{ $order->shipment->destination_city }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Transport</span>
                        <span
                            class="{{ $order->shipment->shipping_included ? 'text-emerald-600' : 'text-orange-500' }} font-medium">
                            {{ $order->shipment->shipping_included ? 'Inclus par le vendeur' : 'À facturer' }}
                        </span>
                    </div>
                </div>

                {{-- Articles --}}
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <p class="text-xs text-gray-500 mb-2">Articles</p>
                    @foreach ($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->product_title }}</span>
                            <span class="text-gray-500">× {{ $item->quantity }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Formulaire validation --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                @if (!$order->shipment->shipping_included)
                    {{-- Transport exclu — saisir les frais --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-5">
                        <p class="text-sm font-bold text-orange-700 mb-1">
                            ⚠ Transport à facturer
                        </p>
                        <p class="text-xs text-orange-600">
                            Le vendeur a payé le transport en espèces.
                            Saisissez le montant exact ci-dessous.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('secretary.deposit.validate', $order) }}">
                    @csrf
                   
                    @if (!$order->shipment->shipping_included)
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Frais de transport payés par le vendeur (FCFA)
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="transport_fee" min="0" required placeholder="Ex: 2500"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm
                                  focus:ring-2 focus:ring-indigo-500
                                  @error('transport_fee') border-red-400 @enderror">
                            @error('transport_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @error('error')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror

                    <button
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold
                           py-3.5 rounded-xl transition">
                        ✓ Valider le dépôt
                    </button>
                </form>

            </div>

            <a href="{{ route('secretary.deposit.page') }}"
                class="block text-center text-sm text-gray-400 hover:underline mt-4">
                ← Annuler et revenir
            </a>

        </div>
    </div>
@endsection
