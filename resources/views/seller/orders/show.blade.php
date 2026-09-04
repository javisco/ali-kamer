@extends('layouts.seller')

@section('title', 'Commande #' . $order->reference)

@section('content')

```
<div class="min-h-screen bg-[#F7F7F2] py-5 sm:py-6">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============================================================
         RETOUR
    ============================================================= --}}
        <div class="mb-4">
            <a href="{{ route('seller.orders.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#016837] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                Retour aux commandes
            </a>
        </div>


        {{-- ============================================================
         EN-TÊTE DE LA COMMANDE
    ============================================================= --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-2.5 mb-1.5">

                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#0a1b12]">
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
                            'paid' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20',
                            'preparing' => 'bg-[#F9A01B]/10 text-[#9A6100] border-[#F9A01B]/25',
                            'registered_origin' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20',
                            'in_transit' => 'bg-[#F9A01B]/10 text-[#9A6100] border-[#F9A01B]/25',
                            'arrived_destination' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20',
                            'awaiting_buyer_confirmation' => 'bg-[#F9A01B]/10 text-[#9A6100] border-[#F9A01B]/25',
                            'completed' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20',
                            'auto_completed' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20',
                            'disputed' => 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20',
                            'cancelled' => 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20',
                            'failed' => 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20',
                        ];

                        $statusLabel =
                            $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));

                        $statusClass =
                            $statusClasses[$order->status] ??
                            'bg-slate-100 text-slate-600 border-slate-200';
                    @endphp

                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-bold {{ $statusClass }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="text-xs sm:text-sm text-slate-500">
                    Créée le {{ $order->created_at->format('d/m/Y') }}
                    à {{ $order->created_at->format('H:i') }}
                </p>

            </div>


            {{-- TOTAL --}}
            <div
                class="bg-white border border-slate-200 rounded-xl px-5 py-3 shadow-sm min-w-[180px] lg:text-right">

                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                    Total commande
                </p>

                <p class="text-xl font-extrabold text-[#0a1b12] mt-0.5">
                    {{ number_format($order->subtotal, 0, ',', ' ') }}
                    <span class="text-xs font-bold text-[#016837]">FCFA</span>
                </p>

            </div>

        </div>


        {{-- ============================================================
         MESSAGE FLASH
    ============================================================= --}}
        @if (session('success'))
            <div
                class="mb-4 flex items-start gap-3 bg-[#016837]/5 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3">

                <div
                    class="w-7 h-7 rounded-lg bg-[#016837]/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <p class="text-sm font-semibold pt-1">
                    {{ session('success') }}
                </p>

            </div>
        @endif


        {{-- ============================================================
         ERREURS
    ============================================================= --}}
        @if ($errors->any())

            <div class="mb-4 bg-[#E30613]/5 border border-[#E30613]/20 rounded-xl px-4 py-3">

                <div class="flex items-start gap-3">

                    <div
                        class="w-7 h-7 rounded-lg bg-[#E30613]/10 text-[#E30613] flex items-center justify-center shrink-0">
                        !
                    </div>

                    <div>
                        <p class="text-sm font-bold text-[#E30613] mb-1">
                            Impossible de préparer l'expédition :
                        </p>

                        <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
         PRÉPARATION DE L'EXPÉDITION
    ============================================================= --}}
        @if ($order->status === \App\Models\Order::STATUS_PAID && $order->shipment)

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-5">

                {{-- EN-TÊTE --}}
                <div class="bg-[#016837]/5 px-5 sm:px-6 py-4 border-b border-[#016837]/15">

                    <div class="flex items-start gap-3">

                        <div
                            class="w-10 h-10 rounded-xl bg-[#016837] text-white flex items-center justify-center shrink-0 shadow-sm">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>

                        </div>

                        <div class="min-w-0">

                            <h2 class="text-base font-extrabold text-[#0a1b12]">
                                Préparer l'expédition
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                Choisissez l'agence et le comptoir où vous déposerez le colis.
                            </p>

                        </div>

                    </div>


                    {{-- Destination --}}
                    <div class="mt-3 flex flex-wrap items-center gap-2">

                        <span class="text-xs font-bold text-[#016837]">
                            Destination :
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5 bg-white border border-[#016837]/20 text-[#0a1b12] px-2.5 py-1 rounded-lg text-xs font-bold">

                            <svg class="w-3.5 h-3.5 text-[#016837]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
                <form method="POST" action="{{ route('seller.orders.prepare', $order) }}"
                    id="prepareShipmentForm" class="p-5 sm:p-6">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                        {{-- AGENCE --}}
                        <div>

                            <label for="agency_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Agence
                            </label>

                            <select name="agency_id" id="agency_id" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#016837]/10 focus:border-[#016837] transition">

                                <option value="">
                                    Sélectionnez une agence
                                </option>

                                @forelse($agencies as $agency)

                                    @php
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

                            <p class="text-[11px] text-slate-400 mt-1.5">
                                Les agences proposées desservent
                                {{ $order->shipment->destination_city }}.
                            </p>

                        </div>


                        {{-- COMPTOIR --}}
                        <div>

                            <label for="counter_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Comptoir de dépôt
                            </label>

                            <select name="counter_id" id="counter_id" required disabled
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#016837]/10 focus:border-[#016837] disabled:cursor-not-allowed disabled:text-slate-400 transition">

                                <option value="">
                                    Sélectionnez d'abord une agence
                                </option>

                            </select>

                            <p id="counterHelp" class="text-[11px] text-slate-400 mt-1.5">
                                Les comptoirs seront affichés après le choix de l'agence.
                            </p>

                        </div>

                    </div>


                    {{-- RÉSUMÉ DU CHOIX --}}
                    <div id="selectionSummary"
                        class="hidden mt-4 bg-[#016837]/5 border border-[#016837]/15 rounded-xl p-3">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-8 h-8 bg-[#016837]/10 text-[#016837] rounded-lg flex items-center justify-center shrink-0">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="text-[10px] uppercase tracking-wide font-bold text-slate-400">
                                    Point de dépôt sélectionné
                                </p>

                                <p id="selectedCounterName"
                                    class="text-sm font-extrabold text-[#0a1b12] mt-0.5 truncate"></p>

                            </div>

                        </div>

                    </div>


                    {{-- ====================================================
                     PROFIL ACHETEUR
                ===================================================== --}}
                    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4">

                        <div class="flex items-center justify-between mb-3">

                            <div>
                                <h2 class="text-sm font-extrabold text-[#0a1b12]">
                                    Profil acheteur
                                </h2>

                                <p class="text-[11px] text-slate-400">
                                    Informations utiles avant l'expédition.
                                </p>
                            </div>

                            <div
                                class="w-8 h-8 rounded-lg bg-[#F9A01B]/10 text-[#F9A01B] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">

                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Nom</span>
                                <span class="font-bold text-slate-800 text-right">
                                    {{ $order->buyer->name }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Téléphone</span>
                                <span class="font-medium text-slate-800">
                                    {{ $order->buyer->phone }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Score de fiabilité</span>

                                <span
                                    class="font-extrabold
                                    {{ $order->buyer->trust_score >= 70
                                        ? 'text-[#016837]'
                                        : ($order->buyer->trust_score >= 40
                                            ? 'text-[#D88900]'
                                            : 'text-[#E30613]') }}">

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

                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Commandes passées</span>
                                <span class="font-bold text-slate-800">
                                    {{ $order->buyer->ordersAsBuyer()->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-3 sm:col-span-2">
                                <span class="text-slate-500">Litiges</span>
                                <span
                                    class="font-bold {{ $order->buyer->dispute_count > 2 ? 'text-[#E30613]' : 'text-slate-800' }}">
                                    {{ $order->buyer->dispute_count }}
                                </span>
                            </div>

                        </div>

                    </div>


                    {{-- INFORMATION --}}
                    <div class="mt-4 bg-[#F9A01B]/10 border border-[#F9A01B]/20 rounded-xl p-3.5">

                        <div class="flex items-start gap-3">

                            <div
                                class="w-7 h-7 rounded-lg bg-[#F9A01B]/15 text-[#D88900] flex items-center justify-center shrink-0 font-bold">
                                i
                            </div>

                            <div>

                                <p class="text-xs font-extrabold text-[#805500]">
                                    Avant de confirmer
                                </p>

                                <p class="text-[11px] text-[#805500] mt-0.5 leading-relaxed">
                                    Vous devrez déposer le colis au comptoir sélectionné.
                                    Celui-ci sera enregistré comme point de départ de
                                    l'expédition vers
                                    <strong>{{ $order->shipment->destination_city }}</strong>.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- BOUTON --}}
                    <div class="mt-4 flex justify-end">

                        <button type="submit" id="prepareButton" disabled
                            class="inline-flex items-center justify-center gap-2 bg-[#016837] hover:bg-[#0a542d] disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm hover:shadow-md transition-all">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                            Confirmer la préparation

                        </button>

                    </div>

                </form>

            </div>

        @endif


        {{-- ============================================================
         CODE DE DÉPÔT DU VENDEUR
    ============================================================= --}}
        @if ($order->status === \App\Models\Order::STATUS_PREPARING && $order->deposit_code)

            <div class="mb-5 bg-[#016837]/5 border border-[#016837]/20 rounded-2xl p-4 sm:p-5">

                <div class="flex items-start gap-3">

                    <div
                        class="w-10 h-10 rounded-xl bg-[#016837] text-white flex items-center justify-center shrink-0 font-bold shadow-sm">
                        ✓
                    </div>

                    <div class="flex-1 min-w-0">

                        <h3 class="text-base font-extrabold text-[#0a1b12]">
                            Colis prêt à être déposé
                        </h3>

                        <p class="text-xs sm:text-sm text-slate-600 mt-1">
                            Présentez le colis au comptoir sélectionné et communiquez
                            ce code au secrétaire.
                        </p>

                        <div class="mt-3 bg-white border border-[#016837]/20 rounded-xl p-3 text-center">

                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Code de dépôt
                            </p>

                            <p class="mt-1.5 text-2xl sm:text-3xl font-black tracking-[0.25em] text-[#0a1b12]">
                                {{ $order->deposit_code }}
                            </p>

                        </div>

                        <div class="mt-3 text-xs text-slate-700 space-y-0.5">
                            <p>
                                <strong>Agence :</strong>
                                {{ $order->shipment->agency->name ?? '—' }}
                            </p>

                            <p>
                                <strong>Comptoir :</strong>
                                {{ $order->shipment->originCounter->full_name ?? '—' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
         FORMULAIRE NOTATION ACHETEUR
    ============================================================= --}}
        @if ($order->isCompleted())

            @php
                $alreadyRated = \App\Models\Review::where('order_id', $order->id)
                    ->where('reviewer_id', auth()->id())
                    ->where('reviewee_type', 'buyer')
                    ->exists();
            @endphp

            @if (!$alreadyRated)

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-5">

                    <div class="flex items-center gap-3 mb-3">

                        <div
                            class="w-9 h-9 rounded-xl bg-[#F9A01B]/15 text-[#D88900] flex items-center justify-center">
                            ★
                        </div>

                        <div>
                            <h2 class="text-sm font-extrabold text-[#0a1b12]">
                                Noter l'acheteur
                            </h2>

                            <p class="text-[11px] text-slate-400">
                                Cette note influence le score de fiabilité de l'acheteur.
                            </p>
                        </div>

                    </div>

                    <form method="POST" action="{{ route('seller.reviews.store', $order) }}"
                        class="space-y-3">

                        @csrf

                        {{-- Étoiles --}}
                        <div>

                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Note <span class="text-[#E30613]">*</span>
                            </label>

                            <div class="flex gap-1.5" id="buyerStars">

                                @for ($i = 1; $i <= 5; $i++)

                                    <button type="button"
                                        onclick="setBuyerRating({{ $i }})"
                                        class="text-2xl text-slate-300 hover:text-[#F9A01B] transition star-buyer"
                                        data-value="{{ $i }}">
                                        ★
                                    </button>

                                @endfor

                            </div>

                            <input type="hidden" name="rating" id="buyer_rating" required>

                            @error('rating')
                                <p class="text-[#E30613] text-[11px] mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Commentaire --}}
                        <div>

                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Commentaire
                                <span class="text-slate-400 font-normal">(optionnel)</span>
                            </label>

                            <textarea name="body" rows="3" maxlength="500"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-[#016837]/10 focus:border-[#016837]"
                                placeholder="Décrivez votre expérience avec cet acheteur..."></textarea>

                        </div>

                        <button type="submit"
                            class="w-full bg-[#F9A01B] hover:bg-[#E89A0A] text-[#0a1b12] font-extrabold py-2.5 rounded-xl transition text-sm shadow-sm">
                            ★ Noter l'acheteur
                        </button>

                    </form>

                    @push('scripts')
                        <script>
                            function setBuyerRating(value) {
                                document.getElementById('buyer_rating').value = value;

                                document.querySelectorAll('.star-buyer').forEach(star => {
                                    const v = parseInt(star.dataset.value);

                                    star.classList.toggle('text-[#F9A01B]', v <= value);
                                    star.classList.toggle('text-slate-300', v > value);
                                });
                            }
                        </script>
                    @endpush

                </div>

            @else

                <div
                    class="bg-[#016837]/5 border border-[#016837]/15 rounded-xl p-3.5 mb-5 text-sm font-semibold text-[#016837]">
                    ✓ Vous avez déjà noté cet acheteur.
                </div>

            @endif

        @endif


        {{-- ============================================================
         CONTENU PRINCIPAL
    ============================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">


            {{-- ========================================================
             ARTICLES COMMANDÉS
        ========================================================= --}}
            <div class="lg:col-span-2">

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                    <div class="px-5 py-4 border-b border-slate-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-base font-extrabold text-[#0a1b12]">
                                    Articles commandés
                                </h2>

                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $order->items->count() }}
                                    {{ $order->items->count() > 1 ? 'articles' : 'article' }}
                                </p>

                            </div>

                            <div
                                class="w-8 h-8 rounded-lg bg-[#016837]/10 text-[#016837] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>

                        </div>

                    </div>


                    {{-- ARTICLES --}}
                    <div class="divide-y divide-slate-100">

                        @forelse($order->items as $item)

                            <div class="p-4 flex gap-3">

                                {{-- IMAGE --}}
                                <div
                                    class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">

                                    @if ($item->product && $item->product->images->first())

                                        <img src="{{ Storage::url($item->product->images->first()->url) }}"
                                            alt="{{ $item->product->title }}"
                                            class="w-full h-full object-cover">

                                    @else

                                        <div
                                            class="w-full h-full flex items-center justify-center text-[10px] text-slate-400">
                                            Aucun visuel
                                        </div>

                                    @endif

                                </div>


                                {{-- INFORMATIONS --}}
                                <div class="flex-1 min-w-0">

                                    <div
                                        class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1.5">

                                        <div class="min-w-0">

                                            <h3 class="text-sm font-extrabold text-[#0a1b12] truncate">
                                                {{ $item->product->title ?? 'Produit supprimé' }}
                                            </h3>

                                            @if ($item->product)

                                                <p class="text-[10px] text-slate-400 mt-0.5">
                                                    Produit #{{ $item->product->id }}
                                                </p>

                                            @endif

                                        </div>


                                        <p class="text-sm font-extrabold text-[#016837] whitespace-nowrap">

                                            {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', ' ') }}

                                            <span class="text-[10px] font-bold text-slate-400">
                                                FCFA
                                            </span>

                                        </p>

                                    </div>


                                    {{-- DÉTAILS --}}
                                    <div class="grid grid-cols-3 gap-3 mt-3">

                                        <div>
                                            <p class="text-[10px] text-slate-400">
                                                Prix unitaire
                                            </p>

                                            <p class="text-xs font-bold text-slate-800 mt-0.5">
                                                {{ number_format($item->unit_price, 0, ',', ' ') }}
                                                FCFA
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[10px] text-slate-400">
                                                Quantité
                                            </p>

                                            <p class="text-xs font-bold text-slate-800 mt-0.5">
                                                {{ $item->quantity }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[10px] text-slate-400">
                                                Sous-total
                                            </p>

                                            <p class="text-xs font-bold text-slate-800 mt-0.5">
                                                {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', ' ') }}
                                                FCFA
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="p-8 text-center">
                                <p class="text-sm text-slate-400">
                                    Aucun article dans cette commande.
                                </p>
                            </div>

                        @endforelse

                    </div>


                    {{-- TOTAL --}}
                    <div class="px-5 py-4 border-t border-slate-200 bg-slate-50">

                        <div class="flex items-center justify-between">

                            <span class="text-xs font-bold text-slate-600">
                                Total de la commande
                            </span>

                            <span class="text-lg font-extrabold text-[#0a1b12]">

                                {{ number_format($order->subtotal, 0, ',', ' ') }}

                                <span class="text-xs text-[#016837]">
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

                    <div class="px-5 py-4 border-b border-slate-200">

                        <h2 class="text-base font-extrabold text-[#0a1b12]">
                            Suivi de la commande
                        </h2>

                        <p class="text-xs text-slate-400 mt-0.5">
                            État actuel de l'expédition.
                        </p>

                    </div>


                    <div class="p-5">

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

                                <div class="flex gap-3">

                                    {{-- Ligne + cercle --}}
                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold
                                            {{ $isCompleted
                                                ? 'bg-[#016837] text-white'
                                                : 'bg-slate-100 text-slate-400 border border-slate-200' }}">

                                            @if ($isCompleted)

                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>

                                            @else

                                                {{ $stepNumber }}

                                            @endif

                                        </div>


                                        @if (!$loop->last)

                                            <div
                                                class="w-px h-10
                                                {{ $currentStep > $stepNumber
                                                    ? 'bg-[#016837]/60'
                                                    : 'bg-slate-200' }}">
                                            </div>

                                        @endif

                                    </div>


                                    {{-- Texte --}}
                                    <div class="pb-5 min-w-0">

                                        <p
                                            class="text-xs font-extrabold
                                            {{ $isCurrent
                                                ? 'text-[#016837]'
                                                : ($isCompleted
                                                    ? 'text-slate-800'
                                                    : 'text-slate-400') }}">
                                            {{ $step['label'] }}
                                        </p>

                                        <p class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">
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

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mt-4 overflow-hidden">

                        <div class="px-4 py-3 border-b border-slate-200">

                            <div class="flex items-center gap-2">

                                <div
                                    class="w-7 h-7 rounded-lg bg-[#F9A01B]/10 text-[#D88900] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>

                                <h3 class="text-sm font-extrabold text-[#0a1b12]">
                                    Informations d'expédition
                                </h3>

                            </div>

                        </div>


                        <div class="p-4 space-y-3">

                            <div>
                                <p class="text-[10px] text-slate-400">
                                    Destination
                                </p>

                                <p class="text-xs font-bold text-slate-800 mt-0.5">
                                    {{ $order->shipment->destination_city }}
                                </p>
                            </div>


                            @if ($order->shipment->originCounter)

                                <div>
                                    <p class="text-[10px] text-slate-400">
                                        Comptoir de départ
                                    </p>

                                    <p class="text-xs font-bold text-slate-800 mt-0.5">
                                        {{ $order->shipment->originCounter->full_name }}
                                    </p>
                                </div>

                            @endif


                            @if ($order->shipment->destinationCounter)

                                <div>
                                    <p class="text-[10px] text-slate-400">
                                        Comptoir de destination
                                    </p>

                                    <p class="text-xs font-bold text-slate-800 mt-0.5">
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
```

================================================================ --}}

```
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

            counterSelect.innerHTML = '';

            counterSelect.disabled = true;

            if (prepareButton) {
                prepareButton.disabled = true;
            }

            if (selectionSummary) {
                selectionSummary.classList.add('hidden');
            }


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


            counterSelect.innerHTML = `
                <option value="">
                    Sélectionnez un comptoir
                </option>
            `;


            counters.forEach(function(counter) {

                const option = document.createElement('option');

                option.value = counter.id;

                option.textContent = counter.name;

                counterSelect.appendChild(option);

            });


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


            if (!counterSelect.value) {

                if (prepareButton) {
                    prepareButton.disabled = true;
                }

                if (selectionSummary) {
                    selectionSummary.classList.add('hidden');
                }

                return;
            }


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
```

@endsection
