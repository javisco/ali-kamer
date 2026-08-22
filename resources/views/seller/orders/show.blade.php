@extends('base')

@section('title', 'Commande #' . $order->reference)

@section('content')

    <div class="min-h-screen bg-slate-50 py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ============================================================
             RETOUR
        ============================================================= --}}
            <div class="mb-6">
                <a href="{{ route('seller.orders.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    Retour aux commandes
                </a>
            </div>


            {{-- ============================================================
             EN-TÊTE DE LA COMMANDE
        ============================================================= --}}
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5 mb-8">

                <div>

                    <div class="flex flex-wrap items-center gap-3 mb-2">

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                            Commande #{{ $order->reference }}
                        </h1>

                        @php
                            $statusLabels = [
                                'pending' => 'En attente',
                                'awaiting_payment' => 'Paiement en attente',
                                'paid' => 'Payée',
                                'preparing' => 'En préparation',
                                'registered_origin' => 'Colis enregistré',
                                'in_transit' => 'En transit',
                                'arrived_destination' => 'Arrivée',
                                'awaiting_buyer_confirmation' => 'Confirmation acheteur',
                                'completed' => 'Terminée',
                                'auto_completed' => 'Terminée automatiquement',
                                'disputed' => 'Litige',
                                'cancelled' => 'Annulée',
                                'failed' => 'Échec',
                            ];

                            $statusClasses = [
                                'paid' => 'bg-emerald-100 text-emerald-700',
                                'preparing' => 'bg-blue-100 text-blue-700',
                                'registered_origin' => 'bg-indigo-100 text-indigo-700',
                                'in_transit' => 'bg-purple-100 text-purple-700',
                                'arrived_destination' => 'bg-cyan-100 text-cyan-700',
                                'awaiting_buyer_confirmation' => 'bg-orange-100 text-orange-700',
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'auto_completed' => 'bg-emerald-100 text-emerald-700',
                                'disputed' => 'bg-red-100 text-red-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'failed' => 'bg-red-100 text-red-700',
                            ];

                            $statusLabel =
                                $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));

                            $statusClass = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600';
                        @endphp

                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                    <p class="text-sm text-slate-500">
                        Créée le {{ $order->created_at->format('d/m/Y') }}
                        à {{ $order->created_at->format('H:i') }}
                    </p>

                </div>


                {{-- TOTAL --}}
                <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4 shadow-sm min-w-[190px]">

                    <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">
                        Total commande
                    </p>

                    <p class="text-2xl font-extrabold text-slate-900 mt-1">
                        {{ number_format($order->total_amount, 0, ',', ' ') }}
                        <span class="text-sm font-bold text-slate-500">
                            FCFA
                        </span>
                    </p>

                </div>

            </div>



            {{-- ============================================================
             MESSAGE FLASH
        ============================================================= --}}
            @if (session('success'))
                <div
                    class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3">

                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <p class="text-sm font-medium">
                        {{ session('success') }}
                    </p>

                </div>
            @endif


            {{-- ============================================================
             ERREURS
        ============================================================= --}}
            @if ($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3">

                    <p class="text-sm font-bold text-red-800 mb-2">
                        Impossible de préparer l'expédition :
                    </p>

                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- ============================================================
             PRÉPARATION DE L'EXPÉDITION
        ============================================================= --}}
            @if ($order->status === \App\Models\Order::STATUS_PAID && $order->shipment)

                <div class="bg-white border border-blue-200 rounded-2xl shadow-sm overflow-hidden mb-8">

                    {{-- En-tête --}}
                    <div class="bg-blue-50 px-6 py-5 border-b border-blue-200">

                        <div class="flex items-start gap-4">

                            <div
                                class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>

                            </div>

                            <div>

                                <h2 class="text-lg font-bold text-blue-900">
                                    Préparer l'expédition
                                </h2>

                                <p class="text-sm text-blue-700 mt-1">
                                    Choisissez l'agence et le comptoir où vous déposerez le colis.
                                </p>

                            </div>

                        </div>


                        {{-- Destination --}}
                        <div class="mt-4 flex flex-wrap items-center gap-2">

                            <span class="text-xs font-semibold text-blue-700">
                                Destination du colis :
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 bg-white border border-blue-200 text-blue-900 px-3 py-1.5 rounded-lg text-sm font-bold">

                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                {{ $order->shipment->destination_city }}

                            </span>

                        </div>

                    </div>


                    {{-- FORMULAIRE --}}
                    <form method="POST" action="{{ route('seller.orders.prepare', $order) }}" id="prepareShipmentForm"
                        class="p-6">

                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            {{-- =================================================
                             AGENCE
                        ================================================== --}}
                            <div>

                                <label for="agency_id" class="block text-sm font-bold text-slate-700 mb-2">
                                    Agence
                                </label>

                                <select name="agency_id" id="agency_id" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition">

                                    <option value="">
                                        Sélectionnez une agence
                                    </option>


                                    @forelse($agencies as $agency)
                                        @php

                                            /*
                                             * On prépare ici les comptoirs de cette agence.
                                             *
                                             * Le contrôleur/service doit avoir chargé
                                             * uniquement les comptoirs correspondant
                                             * à la ville de destination.
                                             */

                                            $counterData = $agency->counters
                                                ->map(function ($counter) {
                                                    return [
                                                        'id' => $counter->id,
                                                        'name' => $counter->full_name,
                                                        'city' => $counter->city,
                                                        'district' => $counter->district,
                                                        'phone' => $counter->phone,
                                                    ];
                                                })
                                                ->values();

                                        @endphp


                                        <option value="{{ $agency->id }}" data-counters='@json($counterData)'>
                                            {{ $agency->name }}
                                        </option>

                                    @empty

                                        <option value="" disabled>
                                            Aucune agence disponible pour cette destination
                                        </option>
                                    @endforelse

                                </select>


                                <p class="text-xs text-slate-400 mt-2">
                                    Les agences proposées desservent
                                    {{ $order->shipment->destination_city }}.
                                </p>

                            </div>



                            {{-- =================================================
                             COMPTOIR
                        ================================================== --}}
                            <div>

                                <label for="counter_id" class="block text-sm font-bold text-slate-700 mb-2">
                                    Comptoir de dépôt
                                </label>


                                <select name="counter_id" id="counter_id" required disabled
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 disabled:cursor-not-allowed disabled:text-slate-400 transition">

                                    <option value="">
                                        Sélectionnez d'abord une agence
                                    </option>

                                </select>


                                <p id="counterHelp" class="text-xs text-slate-400 mt-2">
                                    Les comptoirs seront affichés après le choix de l'agence.
                                </p>

                            </div>

                        </div>



                        {{-- ====================================================
                         RÉSUMÉ DU CHOIX
                    ===================================================== --}}
                        <div id="selectionSummary" class="hidden mt-6 bg-slate-50 border border-slate-200 rounded-xl p-4">

                            <div class="flex items-start gap-3">

                                <div
                                    class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>

                                </div>


                                <div>

                                    <p class="text-xs uppercase tracking-wide font-bold text-slate-400">
                                        Point de dépôt sélectionné
                                    </p>

                                    <p id="selectedCounterName" class="text-sm font-bold text-slate-800 mt-1"></p>

                                </div>

                            </div>

                        </div>

                        {{-- Score de fiabilité de l'acheteur --}}
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mt-2">
                            <h2 class="font-bold text-gray-800 mb-3">Profil acheteur</h2>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nom</span>
                                    <span class="font-medium">{{ $order->buyer->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Téléphone</span>
                                    <span>{{ $order->buyer->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Score de fiabilité</span>
                                    <span
                                        class="font-bold
                {{ $order->buyer->trust_score >= 70
                    ? 'text-emerald-600'
                    : ($order->buyer->trust_score >= 40
                        ? 'text-orange-500'
                        : 'text-red-500') }}">
                                        {{ $order->buyer->trust_score }}/100
                                        @if ($order->buyer->trust_score >= 70)
                                            ✓ Fiable
                                        @elseif($order->buyer->trust_score >= 40)
                                            ⚠ Moyen
                                        @else
                                            ⚠ À surveiller
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Commandes passées</span>
                                    <span>{{ $order->buyer->ordersAsBuyer()->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Litiges</span>
                                    <span
                                        class="{{ $order->buyer->dispute_count > 2 ? 'text-orange-500' : 'text-gray-800' }}">
                                        {{ $order->buyer->dispute_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{-- ====================================================
                         INFORMATION
                    ===================================================== --}}
                        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-4">

                            <div class="flex items-start gap-3">

                                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                </svg>

                                <div>

                                    <p class="text-sm font-bold text-blue-900">
                                        Avant de confirmer
                                    </p>

                                    <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                                        Vous devrez déposer le colis au comptoir sélectionné.
                                        Celui-ci sera enregistré comme point de départ de
                                        l'expédition vers
                                        <strong>{{ $order->shipment->destination_city }}</strong>.
                                    </p>

                                </div>

                            </div>

                        </div>



                        {{-- ====================================================
                         BOUTON
                    ===================================================== --}}
                        <div class="mt-6 flex justify-end">

                            <button type="submit" id="prepareButton" disabled
                                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:text-slate-500 disabled:cursor-not-allowed text-white font-bold text-sm px-6 py-3 rounded-xl shadow-sm transition">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                                Confirmer la préparation

                            </button>

                        </div>

                    </form>

                </div>

            @endif


            {{-- code_deposite du vendeur --}}

            @if ($order->status === \App\Models\Order::STATUS_PREPARING && $order->deposit_code)
                <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-6">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            ✓
                        </div>

                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-emerald-900">
                                Colis prêt à être déposé
                            </h3>

                            <p class="text-sm text-emerald-700 mt-1">
                                Présentez le colis au comptoir sélectionné et communiquez
                                ce code au secrétaire.
                            </p>

                            <div class="mt-4 bg-white border border-emerald-200 rounded-xl p-4 text-center">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Code de dépôt
                                </p>

                                <p class="mt-2 text-3xl font-black tracking-[0.3em] text-slate-900">
                                    {{ $order->deposit_code }}
                                </p>
                            </div>

                            <div class="mt-4 text-sm text-emerald-800">
                                <strong>Agence :</strong>
                                {{ $order->shipment->agency->name ?? '—' }}
                                <br>

                                <strong>Comptoir :</strong>
                                {{ $order->shipment->originCounter->full_name ?? '—' }}
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            {{-- @if (auth()->user()->isSeller())
                @php
                    $buyer = $order->buyer;
                @endphp
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="text-xs {{ $buyer->trust_score >= 70
                            ? 'text-emerald-500'
                            : ($buyer->trust_score >= 40
                                ? 'text-orange-400'
                                : 'text-red-500') }}">
                        Score : {{ $buyer->trust_score }}/100
                    </span>
                    @if ($buyer->prepayment_required)
                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full">
                            ⚠ Prépaiement requis
                        </span>
                    @endif
                </div>
            @endif  --}}

            {{-- Formulaire notation acheteur --}}
            @if ($order->isCompleted())
                @php
                    $alreadyRated = \App\Models\Review::where('order_id', $order->id)
                        ->where('reviewer_id', auth()->id())
                        ->where('reviewee_type', 'buyer')
                        ->exists();
                @endphp

                @if (!$alreadyRated)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-1">Noter l'acheteur</h2>
                        <p class="text-xs text-gray-400 mb-4">
                            Cette note influence le score de fiabilité de l'acheteur.
                        </p>

                        <form method="POST" action="{{ route('seller.reviews.store', $order) }}" class="space-y-4">
                            @csrf

                            {{-- Étoiles --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Note <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2" id="buyerStars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" onclick="setBuyerRating({{ $i }})"
                                            class="text-3xl text-gray-300 hover:text-yellow-400
                                           transition star-buyer"
                                            data-value="{{ $i }}">★</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="buyer_rating" required>
                                @error('rating')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Commentaire --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Commentaire <span class="text-gray-400 font-normal">(optionnel)</span>
                                </label>
                                <textarea name="body" rows="3" maxlength="500"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                                     focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Décrivez votre expérience avec cet acheteur..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold
                               py-3 rounded-xl transition text-sm">
                                ★ Noter l'acheteur
                            </button>
                        </form>

                        @push('scripts')
                            <script>
                                function setBuyerRating(value) {
                                    document.getElementById('buyer_rating').value = value;
                                    document.querySelectorAll('.star-buyer').forEach(star => {
                                        const v = parseInt(star.dataset.value);
                                        star.classList.toggle('text-yellow-400', v <= value);
                                        star.classList.toggle('text-gray-300', v > value);
                                    });
                                }
                            </script>
                        @endpush
                    </div>
                @else
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-sm text-emerald-700">
                        ✓ Vous avez déjà noté cet acheteur.
                    </div>
                @endif
            @endif

            {{-- ============================================================
             CONTENU PRINCIPAL
        ============================================================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                {{-- ========================================================
                 ARTICLES COMMANDÉS
            ========================================================= --}}
                <div class="lg:col-span-2">

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                        <div class="px-6 py-5 border-b border-slate-200">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h2 class="text-lg font-bold text-slate-900">
                                        Articles commandés
                                    </h2>

                                    <p class="text-sm text-slate-400 mt-1">
                                        {{ $order->items->count() }}
                                        {{ $order->items->count() > 1 ? 'articles' : 'article' }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ARTICLES --}}
                        <div class="divide-y divide-slate-100">

                            @forelse($order->items as $item)
                                <div class="p-5 flex gap-4">

                                    {{-- IMAGE --}}
                                    <div
                                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">

                                        @if ($item->product && $item->product->images->first())
                                            <img src="{{ Storage::url($item->product->images->first()->url) }}"
                                                alt="{{ $item->product->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-xs text-slate-400">
                                                Aucun visuel
                                            </div>
                                        @endif

                                    </div>


                                    {{-- INFORMATIONS --}}
                                    <div class="flex-1 min-w-0">

                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">

                                            <div>

                                                <h3 class="font-bold text-slate-900">
                                                    {{ $item->product->title ?? 'Produit supprimé' }}
                                                </h3>

                                                @if ($item->product)
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Produit #{{ $item->product->id }}
                                                    </p>
                                                @endif

                                            </div>


                                            <p class="text-base font-extrabold text-slate-900 whitespace-nowrap">

                                                {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', ' ') }}

                                                <span class="text-xs font-semibold text-slate-400">
                                                    FCFA
                                                </span>

                                            </p>

                                        </div>


                                        {{-- DÉTAILS --}}
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">

                                            <div>

                                                <p class="text-xs text-slate-400">
                                                    Prix unitaire
                                                </p>

                                                <p class="text-sm font-bold text-slate-800 mt-1">
                                                    {{ number_format($item->unit_price, 0, ',', ' ') }}
                                                    FCFA
                                                </p>

                                            </div>


                                            <div>

                                                <p class="text-xs text-slate-400">
                                                    Quantité
                                                </p>

                                                <p class="text-sm font-bold text-slate-800 mt-1">
                                                    {{ $item->quantity }}
                                                </p>

                                            </div>


                                            <div>

                                                <p class="text-xs text-slate-400">
                                                    Sous-total
                                                </p>

                                                <p class="text-sm font-bold text-slate-800 mt-1">

                                                    {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', ' ') }}

                                                    FCFA

                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="p-10 text-center">

                                    <p class="text-sm text-slate-400">
                                        Aucun article dans cette commande.
                                    </p>

                                </div>
                            @endforelse

                        </div>



                        {{-- TOTAL --}}
                        <div class="px-6 py-5 border-t border-slate-200 bg-slate-50">

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-semibold text-slate-600">
                                    Total de la commande
                                </span>

                                <span class="text-xl font-extrabold text-slate-900">

                                    {{ number_format($order->total_amount, 0, ',', ' ') }}

                                    <span class="text-sm">
                                        FCFA
                                    </span>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ========================================================
                 SUIVI
            ========================================================= --}}
                <div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                        <div class="px-6 py-5 border-b border-slate-200">

                            <h2 class="text-lg font-bold text-slate-900">
                                Suivi de la commande
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                État actuel de l'expédition.
                            </p>

                        </div>


                        <div class="p-6">

                            @php

                                $steps = [
                                    [
                                        'status' => 'paid',
                                        'label' => 'Paiement confirmé',
                                        'description' => 'Le paiement de la commande a été confirmé.',
                                    ],
                                    [
                                        'status' => 'preparing',
                                        'label' => 'Préparation',
                                        'description' => 'Le vendeur prépare le colis.',
                                    ],
                                    [
                                        'status' => 'registered_origin',
                                        'label' => 'Colis enregistré',
                                        'description' => 'Le colis a été enregistré au comptoir de départ.',
                                    ],
                                    [
                                        'status' => 'in_transit',
                                        'label' => 'En transit',
                                        'description' => 'Le colis est en cours d’acheminement.',
                                    ],
                                    [
                                        'status' => 'arrived_destination',
                                        'label' => 'Arrivé à destination',
                                        'description' => 'Le colis est arrivé dans la ville de destination.',
                                    ],
                                    [
                                        'status' => 'awaiting_buyer_confirmation',
                                        'label' => 'Confirmation acheteur',
                                        'description' => 'Le colis attend la confirmation de l’acheteur.',
                                    ],
                                    [
                                        'status' => 'completed',
                                        'label' => 'Commande terminée',
                                        'description' => 'La commande est terminée.',
                                    ],
                                ];

                                $statusOrder = [
                                    'paid' => 1,
                                    'preparing' => 2,
                                    'registered_origin' => 3,
                                    'in_transit' => 4,
                                    'arrived_destination' => 5,
                                    'awaiting_buyer_confirmation' => 6,
                                    'completed' => 7,
                                    'auto_completed' => 7,
                                ];

                                $currentStep = $statusOrder[$order->status] ?? 1;

                            @endphp


                            <div class="space-y-0">

                                @foreach ($steps as $index => $step)
                                    @php
                                        $stepNumber = $index + 1;
                                        $isCompleted = $currentStep >= $stepNumber;
                                        $isCurrent = $currentStep === $stepNumber;
                                    @endphp


                                    <div class="flex gap-4">

                                        {{-- Ligne + cercle --}}
                                        <div class="flex flex-col items-center">

                                            <div
                                                class="
                                                w-8 h-8 rounded-full flex items-center justify-center shrink-0
                                                {{ $isCompleted ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400' }}
                                            ">

                                                @if ($isCompleted)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @else
                                                    <span class="text-xs font-bold">
                                                        {{ $stepNumber }}
                                                    </span>
                                                @endif

                                            </div>


                                            @if (!$loop->last)
                                                <div
                                                    class="
                                                    w-px h-12
                                                    {{ $currentStep > $stepNumber ? 'bg-blue-500' : 'bg-slate-200' }}
                                                ">
                                                </div>
                                            @endif

                                        </div>


                                        {{-- Texte --}}
                                        <div class="pb-7">

                                            <p
                                                class="
                                                text-sm font-bold
                                                {{ $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}
                                            ">
                                                {{ $step['label'] }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                                {{ $step['description'] }}
                                            </p>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>



                    {{-- ====================================================
                     INFORMATIONS EXPÉDITION
                ===================================================== --}}
                    @if ($order->shipment)

                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mt-6 overflow-hidden">

                            <div class="px-5 py-4 border-b border-slate-200">

                                <h3 class="font-bold text-slate-900">
                                    Informations d'expédition
                                </h3>

                            </div>


                            <div class="p-5 space-y-4">

                                <div>

                                    <p class="text-xs text-slate-400">
                                        Destination
                                    </p>

                                    <p class="text-sm font-bold text-slate-800 mt-1">
                                        {{ $order->shipment->destination_city }}
                                    </p>

                                </div>


                                @if ($order->shipment->originCounter)
                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Comptoir de départ
                                        </p>

                                        <p class="text-sm font-bold text-slate-800 mt-1">
                                            {{ $order->shipment->originCounter->full_name }}
                                        </p>

                                    </div>
                                @endif


                                @if ($order->shipment->destinationCounter)
                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Comptoir de destination
                                        </p>

                                        <p class="text-sm font-bold text-slate-800 mt-1">
                                            {{ $order->shipment->destinationCounter->full_name }}
                                        </p>

                                    </div>
                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================================
     JAVASCRIPT
================================================================ --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const agencySelect = document.getElementById('agency_id');

            const counterSelect = document.getElementById('counter_id');

            const prepareButton = document.getElementById('prepareButton');

            const counterHelp = document.getElementById('counterHelp');

            const selectionSummary = document.getElementById('selectionSummary');

            const selectedCounterName = document.getElementById('selectedCounterName');


            /*
            |--------------------------------------------------------------------------
            | Vérifier que les éléments existent
            |--------------------------------------------------------------------------
            */

            if (!agencySelect || !counterSelect) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Quand l'agence change
            |--------------------------------------------------------------------------
            */

            agencySelect.addEventListener('change', function() {

                // Réinitialiser
                counterSelect.innerHTML = '';

                counterSelect.disabled = true;

                if (prepareButton) {
                    prepareButton.disabled = true;
                }

                if (selectionSummary) {
                    selectionSummary.classList.add('hidden');
                }


                /*
                |--------------------------------------------------------------------------
                | Aucune agence sélectionnée
                |--------------------------------------------------------------------------
                */

                if (!agencySelect.value) {

                    counterSelect.innerHTML = `
                <option value="">
                    Sélectionnez d'abord une agence
                </option>
            `;

                    if (counterHelp) {
                        counterHelp.textContent =
                            "Les comptoirs seront affichés après le choix de l'agence.";
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Récupérer l'agence sélectionnée
                |--------------------------------------------------------------------------
                */

                const selectedOption =
                    agencySelect.options[agencySelect.selectedIndex];


                let counters = [];


                try {

                    counters = JSON.parse(
                        selectedOption.dataset.counters || '[]'
                    );

                } catch (error) {

                    console.error(
                        'Erreur lors du chargement des comptoirs :',
                        error
                    );

                    counterSelect.innerHTML = `
                <option value="">
                    Impossible de charger les comptoirs
                </option>
            `;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Aucun comptoir
                |--------------------------------------------------------------------------
                */

                if (!counters.length) {

                    counterSelect.innerHTML = `
                <option value="">
                    Aucun comptoir disponible
                </option>
            `;

                    if (counterHelp) {
                        counterHelp.textContent =
                            "Cette agence ne possède aucun comptoir actif dans la ville de destination.";
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Première option
                |--------------------------------------------------------------------------
                */

                counterSelect.innerHTML = `
            <option value="">
                Sélectionnez un comptoir
            </option>
        `;


                /*
                |--------------------------------------------------------------------------
                | Ajouter les comptoirs
                |--------------------------------------------------------------------------
                */

                counters.forEach(function(counter) {

                    const option = document.createElement('option');

                    option.value = counter.id;

                    option.textContent = counter.name;

                    counterSelect.appendChild(option);

                });


                /*
                |--------------------------------------------------------------------------
                | Activer le select
                |--------------------------------------------------------------------------
                */

                counterSelect.disabled = false;


                if (counterHelp) {

                    counterHelp.textContent =
                        counters.length +
                        (counters.length > 1 ?
                            " comptoirs disponibles pour cette destination." :
                            " comptoir disponible pour cette destination.");

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Quand le comptoir change
            |--------------------------------------------------------------------------
            */

            counterSelect.addEventListener('change', function() {

                const selectedOption =
                    counterSelect.options[counterSelect.selectedIndex];


                /*
                |--------------------------------------------------------------------------
                | Aucun comptoir
                |--------------------------------------------------------------------------
                */

                if (!counterSelect.value) {

                    if (prepareButton) {
                        prepareButton.disabled = true;
                    }

                    if (selectionSummary) {
                        selectionSummary.classList.add('hidden');
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Comptoir sélectionné
                |--------------------------------------------------------------------------
                */

                if (prepareButton) {
                    prepareButton.disabled = false;
                }


                if (selectionSummary) {

                    selectionSummary.classList.remove('hidden');

                }


                if (selectedCounterName) {

                    selectedCounterName.textContent =
                        selectedOption.textContent;

                }

            });

        });
    </script>

@endsection
