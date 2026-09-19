@extends('base')

@section('title', 'Colis trouvé')

@section('content')

<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-lg mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">

            <a href="{{ route('secretary.deposit.page') }}"
               class="w-9 h-9 flex items-center justify-center
                      rounded-xl bg-white border border-slate-100
                      text-slate-400 hover:text-primary-600
                      hover:border-success-100 transition">
                ←
            </a>

            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-accent-500">
                    Dépôt vendeur
                </p>

                <h1 class="text-2xl font-extrabold text-slate-900">
                    Colis trouvé
                </h1>
            </div>
        </div>

        {{-- Informations commande --}}
        <div class="bg-white rounded-2xl border border-success-100
                    shadow-sm p-5 mb-5">

            <div class="flex items-center justify-between mb-4">

                <h2 class="font-bold text-slate-900">
                    {{ $order->reference }}
                </h2>

                <span class="bg-success-50 text-primary-600
                             border border-success-100
                             text-xs font-semibold px-3 py-1 rounded-full">
                    En préparation
                </span>
            </div>

            <div class="space-y-2 text-sm">

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Vendeur</span>
                    <span class="font-medium text-slate-900 text-right">
                        {{ $order->shop->name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Destinataire</span>
                    <span class="font-medium text-slate-900 text-right">
                        {{ $order->shipment->recipient_name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Téléphone destinataire</span>
                    <span class="font-medium text-slate-900 text-right">
                        {{ $order->shipment->recipient_phone }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Ville destination</span>
                    <span class="font-bold text-primary-600 text-right">
                        {{ $order->shipment->destination_city }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-slate-500">Transport</span>

                    <span class="{{ $order->shipment->shipping_included
                        ? 'text-primary-600'
                        : 'text-danger' }} font-semibold text-right">

                        {{ $order->shipment->shipping_included
                            ? 'Inclus par le vendeur'
                            : 'À facturer' }}
                    </span>
                </div>

            </div>

            {{-- Articles --}}
            <div class="mt-4 pt-4 border-t border-slate-100">

                <p class="text-[11px] font-bold uppercase tracking-wider
                          text-slate-400 mb-2">
                    Articles
                </p>

                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm py-1">

                        <span class="text-slate-700">
                            {{ $item->purchasedLabel() }}
                        </span>

                        <span class="text-slate-500 font-medium">
                            × {{ $item->quantity }}
                        </span>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Formulaire validation --}}
        <div class="bg-white rounded-2xl border border-slate-100
                    shadow-sm p-5">

            @if (!$order->shipment->shipping_included)

                <div class="bg-warning-50 border border-warning-200
                            rounded-xl p-4 mb-5">

                    <div class="flex items-start gap-3">

                        <div class="w-8 h-8 rounded-lg bg-accent-500/15
                                    flex items-center justify-center shrink-0">
                            <span class="text-accent-500">!</span>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-accent-600">
                                Transport à facturer
                            </p>

                            <p class="text-xs text-accent-600 mt-1">
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
                                      text-slate-700 mb-1.5">
                            Frais de transport payés par le vendeur (FCFA)
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="transport_fee"
                            min="0"
                            required
                            placeholder="Ex: 2500"
                            class="w-full border border-slate-200 bg-slate-50
                                   rounded-xl px-4 py-3 text-sm
                                   focus:outline-none focus:bg-white
                                   focus:border-primary-600
                                   focus:ring-2 focus:ring-success/10
                                   @error('transport_fee')
                                       border-danger
                                   @enderror">

                        @error('transport_fee')
                            <p class="text-danger text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                @endif

                @error('error')
                    <p class="text-danger text-sm mb-4">
                        {{ $message }}
                    </p>
                @enderror

                <button
                    class="w-full bg-primary-600 hover:bg-primary-700
                           active:bg-primary-800 text-white font-bold
                           py-3.5 rounded-xl transition shadow-sm
                           hover:shadow-md">
                    ✓ Valider le dépôt
                </button>

            </form>
        </div>

        <a href="{{ route('secretary.deposit.page') }}"
           class="block text-center text-sm text-slate-400
                  hover:text-primary-600 transition mt-4">
            ← Annuler et revenir
        </a>

    </div>
</div>

@endsection