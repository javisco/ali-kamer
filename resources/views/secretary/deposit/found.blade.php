@extends('base')

@section('title', 'Colis trouvé')

@section('content')

<div class="bg-[#F7F7F2] min-h-screen py-8">
    <div class="max-w-lg mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">

            <a href="{{ route('secretary.deposit.page') }}"
               class="w-9 h-9 flex items-center justify-center
                      rounded-xl bg-white border border-gray-100
                      text-gray-400 hover:text-[#016837]
                      hover:border-green-100 transition">
                ←
            </a>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#F9A01B]">
                    Dépôt vendeur
                </p>

                <h1 class="text-2xl font-extrabold text-gray-900">
                    Colis trouvé
                </h1>
            </div>
        </div>

        {{-- Informations commande --}}
        <div class="bg-white rounded-2xl border border-green-100
                    shadow-sm p-5 mb-5">

            <div class="flex items-center justify-between mb-4">

                <h2 class="font-bold text-gray-900">
                    {{ $order->reference }}
                </h2>

                <span class="bg-green-50 text-[#016837]
                             border border-green-100
                             text-xs font-semibold px-3 py-1 rounded-full">
                    En préparation
                </span>
            </div>

            <div class="space-y-2 text-sm">

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Vendeur</span>
                    <span class="font-medium text-gray-900 text-right">
                        {{ $order->shop->name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Destinataire</span>
                    <span class="font-medium text-gray-900 text-right">
                        {{ $order->shipment->recipient_name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Téléphone destinataire</span>
                    <span class="font-medium text-gray-900 text-right">
                        {{ $order->shipment->recipient_phone }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Ville destination</span>
                    <span class="font-bold text-[#016837] text-right">
                        {{ $order->shipment->destination_city }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500">Transport</span>

                    <span class="{{ $order->shipment->shipping_included
                        ? 'text-[#016837]'
                        : 'text-[#E30613]' }} font-semibold text-right">

                        {{ $order->shipment->shipping_included
                            ? 'Inclus par le vendeur'
                            : 'À facturer' }}
                    </span>
                </div>

            </div>

            {{-- Articles --}}
            <div class="mt-4 pt-4 border-t border-gray-100">

                <p class="text-[11px] font-bold uppercase tracking-wider
                          text-gray-400 mb-2">
                    Articles
                </p>

                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm py-1">

                        <span class="text-gray-700">
                            {{ $item->purchasedLabel() }}
                        </span>

                        <span class="text-gray-500 font-medium">
                            × {{ $item->quantity }}
                        </span>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Formulaire validation --}}
        <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm p-5">

            @if (!$order->shipment->shipping_included)

                <div class="bg-yellow-50 border border-yellow-200
                            rounded-xl p-4 mb-5">

                    <div class="flex items-start gap-3">

                        <div class="w-8 h-8 rounded-lg bg-[#F9A01B]/15
                                    flex items-center justify-center shrink-0">
                            <span class="text-[#F9A01B]">!</span>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-[#9A6500]">
                                Transport à facturer
                            </p>

                            <p class="text-xs text-[#A66D00] mt-1">
                                Le vendeur a payé le transport en espèces.
                                Saisissez le montant exact ci-dessous.
                            </p>
                        </div>

                    </div>
                </div>

            @endif

            <form method="POST"
                  action="{{ route('secretary.deposit.validate', $order) }}">
                @csrf

                @if (!$order->shipment->shipping_included)

                    <div class="mb-5">

                        <label class="block text-sm font-semibold
                                      text-gray-700 mb-1.5">
                            Frais de transport payés par le vendeur (FCFA)
                            <span class="text-[#E30613]">*</span>
                        </label>

                        <input
                            type="number"
                            name="transport_fee"
                            min="0"
                            required
                            placeholder="Ex: 2500"
                            class="w-full border border-gray-200 bg-gray-50
                                   rounded-xl px-4 py-3 text-sm
                                   focus:outline-none focus:bg-white
                                   focus:border-[#016837]
                                   focus:ring-2 focus:ring-green-500/10
                                   @error('transport_fee')
                                       border-red-400
                                   @enderror">

                        @error('transport_fee')
                            <p class="text-[#E30613] text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                @endif

                @error('error')
                    <p class="text-[#E30613] text-sm mb-4">
                        {{ $message }}
                    </p>
                @enderror

                <button
                    class="w-full bg-[#016837] hover:bg-[#0a542d]
                           active:bg-[#064323] text-white font-bold
                           py-3.5 rounded-xl transition shadow-sm
                           hover:shadow-md">
                    ✓ Valider le dépôt
                </button>

            </form>
        </div>

        <a href="{{ route('secretary.deposit.page') }}"
           class="block text-center text-sm text-gray-400
                  hover:text-[#016837] transition mt-4">
            ← Annuler et revenir
        </a>

    </div>
</div>

@endsection