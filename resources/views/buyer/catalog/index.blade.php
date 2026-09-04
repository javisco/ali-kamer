@extends('base')

@section('title', 'Ali-Kamer — Acheter et vendez sans stress')

@section('content')

    {{-- =========================================================
     PAGE D'ACCUEIL
     La navbar et le footer restent entièrement gérés par base.blade.php.
     Cette page contient uniquement le contenu propre à l'accueil.
========================================================= --}}
    <div class="bg-[#FAF9F6] text-slate-800 font-sans">

        {{-- =========================================================
        1. HERO SECTION — ALIGNÉ SUR LA MAQUETTE
    ========================================================== --}}
        <section id="hero-ali-kamer"
            class="relative overflow-hidden bg-gradient-to-r from-[#F3FBF6] via-white to-[#FFFDF3] border-b border-slate-100">

            <div class="relative max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="min-h-[300px] lg:min-h-[322px] grid lg:grid-cols-[1fr_1.05fr] items-center">

                    {{-- Bloc gauche --}}
                    <div class="relative z-10 py-8 lg:py-7">
                        <h1
                            class="text-[44px] sm:text-[54px] lg:text-[62px] leading-[0.98] font-black tracking-tight text-slate-950">
                            <span class="text-[#00843D]">Ali</span><span class="text-[#CE1126]">-</span><span
                                class="text-slate-950">Kamer</span>
                        </h1>

                        <h2 class="mt-1 text-[25px] sm:text-[30px] lg:text-[35px] leading-tight font-black text-slate-950">
                            Construit au Cameroun
                            <span class="block text-[#00843D]">pour l'Afrique</span>
                        </h2>

                        <p class="mt-3 max-w-[480px] text-sm sm:text-[15px] leading-relaxed text-slate-700">
                            La marketplace qui vous connecte aux meilleures
                            <br class="hidden sm:block">
                            opportunités, en toute confiance.
                        </p>

                        <a href="#produits"
                            class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#00843D] hover:bg-[#006B32]
                              px-5 py-2.5 text-sm font-black text-white shadow-md shadow-emerald-900/15
                              transition hover:-translate-y-0.5">
                            Découvrir nos produits
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <div
                            class="mt-5 flex flex-wrap items-center gap-x-7 gap-y-2.5 text-[11px] font-bold text-slate-700">
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-[#00843D] text-white">✓</span>
                                Achat sécurisé
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-[#00843D] text-white">▣</span>
                                Paiement Mobile Money
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-[#00843D] text-white">↗</span>
                                Livraison interurbaine
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-[#00843D] text-white">★</span>
                                Satisfaction garantie
                            </div>
                        </div>
                    </div>

                    {{-- Bloc droit : l'Afrique, avec le Cameroun mis en exergue --}}
                    <div
                        class="relative h-full min-h-[300px] flex items-center justify-center lg:justify-end pr-0 lg:pr-24">

                        {{-- Halo continental --}}
                        <div
                            class="absolute inset-y-4 left-8 right-20 rounded-full
                                bg-[radial-gradient(circle_at_center,rgba(0,132,61,.15),transparent_68%)]">
                        </div>

                        {{-- Afrique stylisée : le Cameroun est le point de départ mis en évidence --}}
                        <div class="relative z-10 w-[250px] sm:w-[285px] lg:w-[330px]">
                            <svg viewBox="0 0 360 390" class="w-full h-auto drop-shadow-md"
                                aria-label="Afrique avec le Cameroun mis en évidence">
                                <defs>
                                    <linearGradient id="akAfricaGreen" x1="0" y1="0" x2="1"
                                        y2="1">
                                        <stop offset="0%" stop-color="#006B32" />
                                        <stop offset="55%" stop-color="#00843D" />
                                        <stop offset="100%" stop-color="#0A9B4E" />
                                    </linearGradient>
                                    <linearGradient id="akCameroon" x1="0" y1="0" x2="1"
                                        y2="1">
                                        <stop offset="0%" stop-color="#CE1126" />
                                        <stop offset="50%" stop-color="#FCD116" />
                                        <stop offset="100%" stop-color="#00843D" />
                                    </linearGradient>
                                    <filter id="akGlow" x="-60%" y="-60%" width="220%" height="220%">
                                        <feGaussianBlur stdDeviation="7" result="blur" />
                                        <feMerge>
                                            <feMergeNode in="blur" />
                                            <feMergeNode in="SourceGraphic" />
                                        </feMerge>
                                    </filter>
                                </defs>

                                {{-- Silhouette africa --}}
                                <path
                                    d="M164 17c31-10 66 0 79 25 12 23 8 39 31 53 20 13 34 38 27 58-6 17-22 26-21 44 2 25 29 43 20 67-9 26-39 33-52 51-13 18-14 47-33 55-18 8-39-11-56-27-17-16-36-24-46-44-10-19-7-41-18-58-13-19-38-30-46-53-9-23 4-47 21-64 17-17 33-29 41-51 7-23 17-55 53-56z"
                                    fill="url(#akAfricaGreen)" stroke="#006B32" stroke-width="4" />

                                {{-- Lignes décoratives discrètes pour donner une lecture continentale --}}
                                <path d="M91 106c38 12 75 14 113 5 34-8 61-5 88 10" fill="none" stroke="#FCD116"
                                    stroke-width="3" opacity=".65" />
                                <path d="M78 207c40-9 76-3 112 12 36 15 68 17 99 7" fill="none" stroke="#FFFFFF"
                                    stroke-width="2.5" opacity=".32" />
                                <path d="M104 286c34-14 70-13 106 2 31 13 52 13 75 4" fill="none" stroke="#CE1126"
                                    stroke-width="3" opacity=".55" />

                                {{-- Zone Cameroun mise en exergue --}}
                                <g filter="url(#akGlow)">
                                    <circle cx="139" cy="178" r="27" fill="#CE1126" opacity=".24" />
                                    <circle cx="139" cy="178" r="18" fill="url(#akCameroon)" stroke="#FFFFFF"
                                        stroke-width="4" />
                                    <circle cx="139" cy="178" r="7" fill="#FCD116" />
                                </g>

                                {{-- Étoile camerounaise --}}
                                <path d="M139 165l3.7 9 9.7.8-7.4 6.2 2.3 9.4-8.3-5-8.3 5 2.3-9.4-7.4-6.2 9.7-.8z"
                                    fill="#FCD116" stroke="#FFFFFF" stroke-width="1.5" />

                                {{-- Trait vers le label Cameroun --}}
                                <path d="M151 178 C174 168, 190 165, 211 166" fill="none" stroke="#CE1126"
                                    stroke-width="3" />
                                <circle cx="214" cy="166" r="4" fill="#FCD116" stroke="#CE1126"
                                    stroke-width="2" />

                                {{-- Label --}}
                                <g>
                                    <rect x="207" y="139" width="110" height="48" rx="12" fill="white"
                                        stroke="#CE1126" stroke-width="2" />
                                    <text x="218" y="158" font-family="Arial, sans-serif" font-size="10"
                                        font-weight="800" fill="#CE1126">🇨🇲 CAMEROUN</text>
                                    <text x="218" y="174" font-family="Arial, sans-serif" font-size="8.5"
                                        font-weight="700" fill="#334155">Notre point de départ</text>
                                </g>
                            </svg>

                            <div
                                class="absolute -bottom-1 left-1/2 -translate-x-1/2 whitespace-nowrap
                                    rounded-full border border-[#FCD116]/70 bg-white/95 px-4 py-1.5
                                    text-[11px] font-black text-[#006B32] shadow-sm">
                                🇨🇲 Né au Cameroun · Pensé pour l'Afrique
                            </div>
                        </div>

                        {{-- Signature de marque --}}
                        <div class="relative z-20 hidden sm:block ml-[-4px] lg:ml-[-12px] max-w-[155px]">
                            <p class="font-serif text-[28px] lg:text-[32px] italic leading-[0.95] text-slate-900">
                                Acheter<br>
                                et vendez<br>
                                <span class="text-[#00843D]">sans stress</span>
                            </p>
                            <div class="mt-3 h-1.5 w-28 rotate-[-7deg] rounded-full bg-[#FCD116]"></div>
                            <p class="mt-3 text-[10px] font-black uppercase tracking-wide text-[#CE1126]">
                                Du Cameroun pour l'Afrique
                            </p>
                        </div>

                        {{-- Garanties à droite --}}
                        <div class="absolute right-0 top-1/2 z-30 hidden xl:flex -translate-y-1/2 flex-col gap-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#00843D] text-lg text-white">🔒</span>
                                <span class="w-28 text-xs font-bold leading-tight text-slate-700">Des
                                    transactions<br>sécurisées</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#00843D] text-lg text-white">🚚</span>
                                <span class="w-28 text-xs font-bold leading-tight text-slate-700">Un réseau<br>d'agences
                                    fiable</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#00843D] text-lg text-white">📱</span>
                                <span class="w-28 text-xs font-bold leading-tight text-slate-700">MTN MoMo<br>Orange
                                    Money</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#00843D] text-lg text-white">👥</span>
                                <span class="w-28 text-xs font-bold leading-tight text-slate-700">Une plateforme<br>100%
                                    africaine</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- =========================================================
         2. CATÉGORIES
    ========================================================== --}}
        <section class="bg-[#FAF9F6]">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

                <div class="flex items-end justify-between gap-4 mb-5">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-wider text-[#00843D]">
                            Explorez
                        </p>
                        <h2 class="mt-1 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                            Nos catégories
                        </h2>
                    </div>

                    <a href="{{ route('buyer.home') }}"
                        class="text-xs font-extrabold text-[#00843D] hover:text-[#006B32]">
                        Voir toutes
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                @php
                    $categoriesList = [
                        ['name' => 'Produits agricoles', 'slug' => 'agriculture', 'icon' => '🍃', 'tone' => 'green'],
                        ['name' => 'Élevage', 'slug' => 'elevage', 'icon' => '🐄', 'tone' => 'yellow'],
                        ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🧺', 'tone' => 'red'],
                        ['name' => 'Téléphonie', 'slug' => 'telephonie', 'icon' => '📱', 'tone' => 'green'],
                        ['name' => 'Informatique', 'slug' => 'informatique', 'icon' => '💻', 'tone' => 'yellow'],
                        ['name' => 'Maison & Bureau', 'slug' => 'maison', 'icon' => '🏠', 'tone' => 'green'],
                        ['name' => 'Mode & Beauté', 'slug' => 'mode', 'icon' => '👕', 'tone' => 'red'],
                        ['name' => 'Équipements', 'slug' => 'equipements', 'icon' => '⚙️', 'tone' => 'yellow'],
                        ['name' => 'Livres & Formation', 'slug' => 'livres', 'icon' => '📖', 'tone' => 'green'],
                        ['name' => 'Autres', 'slug' => 'autres', 'icon' => '•••', 'tone' => 'red'],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2.5 sm:gap-3">
                    @foreach ($categoriesList as $cat)
                        @php
                            $toneClasses = match ($cat['tone']) {
                                'red' => 'bg-red-50 text-[#CE1126] group-hover:bg-red-100',
                                'yellow' => 'bg-yellow-50 text-[#8A7000] group-hover:bg-yellow-100',
                                default => 'bg-emerald-50 text-[#00843D] group-hover:bg-emerald-100',
                            };
                        @endphp

                        <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}"
                            class="group min-w-0 flex flex-col items-center text-center
                              p-3 sm:p-3.5 rounded-2xl bg-white
                              border {{ request('category') === $cat['slug'] ? 'border-[#00843D] ring-2 ring-emerald-100' : 'border-slate-200' }}
                              hover:border-[#00843D] hover:-translate-y-0.5 hover:shadow-md
                              transition-all duration-200">

                            <span
                                class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl
                                     flex items-center justify-center text-2xl
                                     {{ $toneClasses }} transition">
                                {{ $cat['icon'] }}
                            </span>

                            <span
                                class="mt-2 text-[10px] sm:text-xs font-extrabold
                                     text-slate-800 group-hover:text-[#00843D]
                                     leading-tight">
                                {{ $cat['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- =========================================================
         3. PRODUITS
    ========================================================== --}}
        <section id="produits" class="bg-white border-y border-slate-100 scroll-mt-24">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-9 sm:py-11">

                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-wider text-[#00843D]">
                            La sélection Ali-Kamer
                        </p>

                        <h2 class="mt-1 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                            @if (request('q'))
                                Résultats pour
                                <span class="text-[#00843D]">"{{ request('q') }}"</span>
                            @elseif(request('category'))
                                Produits :
                                <span class="text-[#00843D]">{{ request('category') }}</span>
                            @else
                                Produits populaires
                            @endif
                        </h2>

                        <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium">
                            {{ $products->total() }}
                            {{ $products->total() > 1 ? 'produits disponibles' : 'produit disponible' }}
                        </p>
                    </div>

                    {{-- FILTRES --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                        <a href="{{ route('buyer.home') }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ !request('category')
                                  ? 'bg-[#00843D] text-white shadow-sm'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                            Tous
                        </a>

                        <a href="{{ route('buyer.home', ['category' => 'agriculture']) }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ request('category') === 'agriculture'
                                  ? 'bg-[#00843D] text-white'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                            Agriculture
                        </a>

                        <a href="{{ route('buyer.home', ['category' => 'elevage']) }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ request('category') === 'elevage'
                                  ? 'bg-[#00843D] text-white'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                            Élevage
                        </a>

                        <a href="{{ route('buyer.home', ['category' => 'electronique']) }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ request('category') === 'electronique'
                                  ? 'bg-[#00843D] text-white'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                            Électronique
                        </a>

                        <a href="{{ route('buyer.home', ['category' => 'mode']) }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ request('category') === 'mode'
                                  ? 'bg-[#00843D] text-white'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                            Mode
                        </a>

                        <a href="{{ route('buyer.home', ['category' => 'maison']) }}"
                            class="shrink-0 px-4 py-2 rounded-full text-xs font-extrabold transition
                              {{ request('category') === 'maison'
                                  ? 'bg-[#00843D] text-white'
                                  : 'bg-[#FAF9F6] border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                            Maison
                        </a>
                    </div>
                </div>


                {{-- GRILLE PRODUITS --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2.5 sm:gap-3">

                    @forelse($products as $product)

                        @php
                            $availableStock = max(0, (int) $product->stock - (int) $product->stock_reserved);

                            $hasDiscount = $product->old_price && $product->old_price > $product->price;
                            $discountPercent = 0;

                            if ($hasDiscount && $product->old_price > 0) {
                                $discountPercent = (int) round(
                                    (($product->old_price - $product->price) / $product->old_price) * 100,
                                );
                            }

                            $minQuantity = max(1, (int) ($product->min_quantity ?? 1));

                            $sellerAge = null;

                            if ($product->shop?->user?->created_at) {
                                $createdAt = $product->shop->user->created_at;
                                $now = now();

                                $years = (int) $createdAt->diffInYears($now);
                                $months = (int) $createdAt->diffInMonths($now);
                                $days = (int) $createdAt->diffInDays($now);

                                if ($years >= 1) {
                                    $sellerAge = $years . ' ' . ($years > 1 ? 'ans' : 'an');
                                } elseif ($months >= 1) {
                                    $sellerAge = $months . ' mois';
                                } elseif ($days >= 1) {
                                    $sellerAge = $days . ' ' . ($days > 1 ? 'jours' : 'jour');
                                } else {
                                    $sellerAge = "moins d'un jour";
                                }
                            }

                            $salesCount = \App\Models\OrderItem::query()
                                ->where('product_id', $product->id)
                                ->whereHas('order', function ($query) {
                                    $query->whereIn('status', [
                                        \App\Models\Order::STATUS_COMPLETED,
                                        \App\Models\Order::STATUS_AUTO_COMPLETED,
                                    ]);
                                })
                                ->sum('quantity');
                        @endphp


                        {{-- CARTE PRODUIT --}}
                        <a href="{{ route('product.show', $product) }}"
                            class="group min-w-0 bg-white border border-slate-200 rounded-2xl
                              overflow-hidden hover:border-[#00843D]
                              hover:shadow-lg hover:-translate-y-0.5
                              transition-all duration-200 flex flex-col">

                            {{-- IMAGE --}}
                            <div class="relative aspect-[1.12/1] bg-slate-100 overflow-hidden">

                                @if ($product->images && $product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->title }}" loading="lazy"
                                        class="w-full h-full object-cover
                                            group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center
                                            bg-[#FAF9F6] text-slate-300">
                                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                ```blade
                                {{-- INFORMATIONS TRANSPORT --}}
                                @if ($product->shipping_included)
                                    <span
                                        class="absolute top-2 left-2 inline-flex items-center gap-1
                 px-2 py-1 rounded-md
                 bg-[#00843D] text-white
                 text-[9px] font-black shadow-md
                 border border-white/20">
                                        <span class="text-[10px]">🚚</span>
                                        Transport inclus
                                    </span>
                                @else
                                    <span
                                        class="absolute top-2 left-2 inline-flex items-center gap-1
                 px-2 py-1 rounded-md
                 bg-[#F9A01B] text-slate-950
                 text-[9px] font-black shadow-md
                 border border-white/30">
                                        <span class="text-[10px]">🚚</span>
                                        Transport non inclus
                                    </span>
                                @endif
                                ```


                                @if ($hasDiscount && $discountPercent > 0)
                                    <span
                                        class="absolute top-2 right-2 px-2 py-1 rounded-md
                                             bg-[#CE1126] text-white text-[10px] font-black shadow-md">
                                        -{{ $discountPercent }}%
                                    </span>
                                @endif

                                @if ($availableStock <= 0 || $product->status === 'sold_out')
                                    <span
                                        class="absolute bottom-2 right-2 px-2 py-1 rounded-md
                                             bg-slate-900/90 text-white text-[9px] font-bold">
                                        Épuisé
                                    </span>
                                @elseif ($availableStock <= 5)
                                    <span
                                        class="absolute bottom-2 right-2 px-2 py-1 rounded-md
                                             bg-[#FCD116] text-slate-900 text-[9px] font-black">
                                        {{ $availableStock }} restant(s)
                                    </span>
                                @endif
                            </div>


                            {{-- INFORMATIONS --}}
                            <div class="p-2.5 sm:p-3 flex flex-col flex-1">

                                @if ($product->shop)
                                    <div class="flex items-center gap-1 min-w-0">
                                        <span class="text-[11px] font-extrabold text-slate-600 truncate">
                                            {{ $product->shop->name }}
                                        </span>

                                        @if ($product->shop->verified_at)
                                            <svg class="w-3.5 h-3.5 text-[#00843D] shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414 0L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5a1 1 0 000-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5">
                                        <span class="truncate">
                                            📍 {{ $product->city ?? ($product->shop->city ?? 'Cameroun') }}
                                        </span>

                                        @if ($sellerAge)
                                            <span>·</span>
                                            <span class="shrink-0">{{ $sellerAge }}</span>
                                        @endif
                                    </div>
                                @endif

                                <h3
                                    class="mt-1.5 text-xs sm:text-sm font-bold text-slate-900
                                       leading-snug line-clamp-2
                                       group-hover:text-[#00843D] transition">
                                    {{ $product->title }}
                                </h3>

                                <div class="mt-1 flex items-center gap-1.5 text-[10px] text-slate-500">
                                    <span class="font-bold text-slate-700">
                                        {{ number_format($salesCount, 0, ',', ' ') }}
                                    </span>
                                    {{ $salesCount > 1 ? 'ventes' : 'vente' }}
                                    <span class="text-slate-300">•</span>
                                    <span
                                        class="inline-flex items-center rounded-md bg-emerald-50 px-1.5 py-0.5
                                             text-[9px] font-black text-[#006B32]">
                                        Min. {{ $minQuantity }}
                                    </span>
                                </div>

                                <div class="mt-auto pt-2.5 flex items-end justify-between gap-1">
                                    <div class="min-w-0">

                                        <p class="text-base sm:text-[17px] font-black text-[#00843D] leading-none">
                                            {{ number_format($product->price, 0, ',', ' ') }}
                                            <span class="text-[10px] font-bold">FCFA</span>
                                        </p>

                                        @if ($hasDiscount)
                                            <p class="text-[10px] text-[#CE1126] line-through mt-1 font-semibold">
                                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                            </p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </a>

                    @empty

                        <div class="col-span-full">
                            <div
                                class="bg-[#FAF9F6] border border-dashed border-slate-300
                                    rounded-3xl p-10 sm:p-12 text-center max-w-md mx-auto">

                                <div
                                    class="w-14 h-14 bg-white rounded-2xl flex items-center
                                        justify-center mx-auto text-2xl text-slate-400 mb-3
                                        border border-slate-200">
                                    🔍
                                </div>

                                <h3 class="text-base font-black text-slate-900">
                                    Aucun produit trouvé
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    Essayez de modifier votre recherche ou de réinitialiser vos filtres.
                                </p>

                                @if (request('q') || request('category'))
                                    <a href="{{ route('buyer.home') }}"
                                        class="inline-flex items-center mt-4 px-4 py-2 rounded-xl
                                          bg-[#00843D] text-white text-xs font-bold
                                          hover:bg-[#006B32] transition">
                                        Voir tous les produits
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </section>


        {{-- =========================================================
         4. COMMENT ÇA MARCHE
    ========================================================== --}}
        <section class="bg-[#FAF9F6]">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">

                <div
                    class="bg-white border border-slate-200 rounded-[2rem]
                        overflow-hidden shadow-sm">

                    <div class="p-6 sm:p-8 lg:p-10">

                        <div class="max-w-2xl">
                            <p class="text-[11px] font-black uppercase tracking-wider text-[#00843D]">
                                Simple, rapide et sécurisé
                            </p>

                            <h2 class="mt-1 text-xl sm:text-2xl lg:text-3xl font-black text-slate-900">
                                Comment ça marche ?
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                De la recherche jusqu'au retrait de votre colis,
                                Ali-Kamer simplifie chaque étape.
                            </p>
                        </div>

                        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

                            <div class="relative">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="w-10 h-10 rounded-2xl bg-[#00843D]
                                             text-white flex items-center justify-center
                                             font-black shrink-0">
                                        1
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900">
                                            Trouvez
                                        </h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            Recherchez et choisissez le produit qui vous convient.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="w-10 h-10 rounded-2xl bg-[#FCD116]
                                             text-slate-900 flex items-center justify-center
                                             font-black shrink-0">
                                        2
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900">
                                            Commandez
                                        </h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            Passez votre commande avec les informations nécessaires.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="w-10 h-10 rounded-2xl bg-[#CE1126]
                                             text-white flex items-center justify-center
                                             font-black shrink-0">
                                        3
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900">
                                            Payez
                                        </h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            Utilisez vos moyens de paiement locaux disponibles.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="w-10 h-10 rounded-2xl bg-[#00843D]
                                             text-white flex items-center justify-center
                                             font-black shrink-0">
                                        4
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900">
                                            Recevez
                                        </h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            Retirez votre colis dans l'agence prévue.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="border-t border-slate-100 bg-gradient-to-r from-emerald-50 via-white to-yellow-50">
                        <div
                            class="p-5 sm:p-6 flex flex-col sm:flex-row
                                items-start sm:items-center justify-between gap-5">

                            <div class="flex items-center gap-3">
                                <span
                                    class="w-11 h-11 rounded-2xl bg-[#FCD116]
                                         flex items-center justify-center text-xl">
                                    🤝
                                </span>

                                <div>
                                    <h3 class="text-sm font-black text-slate-900">
                                        Ali-Kamer, plus qu'une marketplace.
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Une plateforme construite au Cameroun pour l'Afrique.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('about') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                                  bg-[#CE1126] hover:bg-red-700 text-white
                                  text-xs font-extrabold transition shrink-0">
                                Découvrir Ali-Kamer
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 5l7 7-7 7M20 12H4" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- =========================================================
         5. CHIFFRES / PROMESSE DE MARQUE
         Valeurs présentées comme éléments marketing de la maquette.
    ========================================================== --}}
        <section class="bg-white border-t border-slate-100">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-0
                        divide-y sm:divide-y-0 sm:divide-x divide-slate-200">

                    <div class="px-4 py-4 sm:px-6 text-center">
                        <p class="text-2xl sm:text-3xl font-black text-[#00843D]">
                            100%
                        </p>
                        <p class="mt-1 text-xs font-bold text-slate-500">
                            Pensé pour le Cameroun
                        </p>
                    </div>

                    <div class="px-4 py-4 sm:px-6 text-center">
                        <p class="text-2xl sm:text-3xl font-black text-[#CE1126]">
                            24/7
                        </p>
                        <p class="mt-1 text-xs font-bold text-slate-500">
                            Marketplace accessible
                        </p>
                    </div>

                    <div class="px-4 py-4 sm:px-6 text-center">
                        <p class="text-2xl sm:text-3xl font-black text-[#8A7000]">
                            XAF
                        </p>
                        <p class="mt-1 text-xs font-bold text-slate-500">
                            Paiement en FCFA
                        </p>
                    </div>

                    <div class="px-4 py-4 sm:px-6 text-center">
                        <p class="text-2xl sm:text-3xl font-black text-[#00843D]">
                            🇨🇲 → 🌍
                        </p>
                        <p class="mt-1 text-xs font-bold text-slate-500">
                            Construit au Cameroun pour l'Afrique
                        </p>
                    </div>

                </div>
            </div>
        </section>

    </div>

@endsection
