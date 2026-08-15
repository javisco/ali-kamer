@extends('base')

@section('title', 'Interface Secrétaire')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">

            {{-- En-tête --}}
            <div class="mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Interface Secrétaire</h1>
                @if ($counter)
                    {{-- Afficher le guichet principal du secrétaire --}}
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $counter->full_name }}
                    </p>
                @else
                    {{-- Alerte si aucun guichet assigné --}}
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mt-3 text-sm">
                        ⚠ Aucun guichet assigné à votre compte. Contactez l'administrateur.
                    </div>
                @endif
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── SECTION 1 : Enregistrer un dépôt ─────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
                <h2 class="font-bold text-gray-800 mb-1">📦 Enregistrer un dépôt</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Le vendeur vous donne un code. Saisissez-le pour enregistrer le colis.
                </p>

                <form method="POST" action="{{ route('secretary.deposit') }}" class="flex gap-3">
                    @csrf
                    <input type="text" name="deposit_code" placeholder="Code 8 caractères" maxlength="8"
                        class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm
                          uppercase tracking-widest font-mono focus:ring-2 focus:ring-indigo-500"
                        autofocus>
                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                           px-6 py-3 rounded-xl transition text-sm">
                        Valider
                    </button>
                </form>
                @error('deposit_code')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── SECTION 2 : Colis à valider à l'arrivée ──────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
                <h2 class="font-bold text-gray-800 mb-4">
                    🚚 Colis à valider à l'arrivée
                    @if ($pendingArrival->count())
                        <span
                            class="ml-2 bg-orange-100 text-orange-600 text-xs font-bold
                             px-2 py-0.5 rounded-full">
                            {{ $pendingArrival->count() }}
                        </span>
                    @endif
                </h2>

                @if ($pendingArrival->isEmpty())
                    <p class="text-gray-400 text-sm">Aucun colis en attente de validation.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($pendingArrival as $order)
                            <div class="border border-gray-100 rounded-xl p-4">

                                {{-- Infos commande --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $order->reference }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Vendeur : {{ $order->shop->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Destinataire : {{ $order->shipment->recipient_name }}
                                            — {{ $order->shipment->recipient_phone }}
                                        </p>
                                    </div>
                                    <span
                                        class="text-xs font-semibold px-2 py-1 rounded-full
                                         bg-orange-100 text-orange-600">
                                        {{ $order->status === 'in_transit' ? 'En transit' : 'Enregistré' }}
                                    </span>
                                </div>

                                {{-- Formulaire validation arrivée --}}
                                <form method="POST" action="{{ route('secretary.arrival', $order) }}" class="space-y-3">
                                    @csrf

                                    {{-- Frais transport si non inclus --}}
                                    @if (!$order->shipment->shipping_included)
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                                Frais de transport (FCFA)
                                                {{-- Payés en main propre par le vendeur --}}
                                                <span class="text-gray-400 font-normal">
                                                    — saisis après paiement en main propre
                                                </span>
                                            </label>
                                            <input type="number" name="transport_fee" min="0" placeholder="Ex: 2500"
                                                class="w-full border border-gray-300 rounded-xl
                                                  px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                    @endif

                                    <button
                                        class="w-full bg-teal-600 hover:bg-teal-700 text-white
                                          font-bold py-2.5 rounded-xl transition text-sm">
                                        ✓ Valider l'arrivée
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── SECTION 3 : Remise colis avec OTP ────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-1">🔑 Remise de colis</h2>
                <p class="text-sm text-gray-500 mb-4">
                    L'acheteur vous donne verbalement son code OTP. Saisissez-le pour
                    confirmer la remise et déclencher le paiement du vendeur.
                </p>

                {{-- Rechercher la commande par référence puis saisir l'OTP --}}
                <div x-data="{ step: 1, order: null, reference: '' }">


                    {{-- Résultat de la recherche --}}

                    {{-- Formulaire OTP --}}

                    <form method="GET" action="{{ route('secretary.search') }}">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Référence de la commande
                        </label>

                        <div class="flex gap-2">

                            <input type="text" name="ref" value="{{ request('ref') }}" placeholder="ALK-2026-00001"
                                required class="flex-1 rounded-xl border-gray-300 border-2 p-1">

                            <button type="submit" class="px-6 py-3 rounded-xl bg-slate-800 text-white font-semibold">
                                Rechercher
                            </button>

                        </div>

                    </form>


                    @if (isset($searchedOrder))
                        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">

                            <div class="font-semibold text-blue-900">
                                Commande {{ $searchedOrder->reference }}
                            </div>

                            <div class="text-sm text-blue-700 mt-1">
                                Acheteur :
                                {{ $searchedOrder->buyer->name }}
                            </div>

                            <div class="text-sm text-blue-700">
                                Téléphone :
                                {{ $searchedOrder->shipment->recipient_phone }}
                            </div>

                            <div class="text-sm text-blue-700">
                                Destination :
                                {{ $searchedOrder->shipment->destination_city }}
                            </div>

                            <div class="text-sm text-blue-700">
                                Comptoir :
                                {{ $searchedOrder->shipment->destination_counter_id }}
                            </div>

                            <form method="POST" action="{{ route('secretary.otp', $searchedOrder) }}" class="mt-4">
                                @csrf

                                <label class="block text-sm font-medium text-gray-700">
                                    Code OTP de l'acheteur
                                </label>

                                <div class="flex gap-2 mt-2">

                                    <input type="text" name="otp" maxlength="6" inputmode="numeric" required
                                        class="flex-1 rounded-xl border-gray-300  border-2 p-1 " placeholder="000000   ">

                                    <button type="submit"
                                        class="px-6 py-3 rounded-xl bg-emerald-600 text-white font-semibold">
                                        Remettre
                                    </button>

                                </div>

                            </form>

                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    </div>


@endsection
