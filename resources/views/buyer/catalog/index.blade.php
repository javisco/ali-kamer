@extends('base')

@section('title', 'Ali-Kamer — Acheter et vendre sans stress')

@section('content')

    @php
        // Source unique de vérité pour toutes les catégories
        $categoriesList = [
            ['name' => 'Agriculture', 'slug' => 'agriculture', 'icon' => '🌿', 'color' => 'green'],
            ['name' => 'Élevage', 'slug' => 'elevage', 'icon' => '🐄', 'color' => 'amber'],
            ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🧺', 'color' => 'orange'],
            ['name' => 'Téléphonie', 'slug' => 'telephonie', 'icon' => '📱', 'color' => 'green'],
            ['name' => 'Informatique', 'slug' => 'informatique', 'icon' => '💻', 'color' => 'blue'],
            ['name' => 'Maison', 'slug' => 'maison', 'icon' => '🏠', 'color' => 'green'],
            ['name' => 'Mode', 'slug' => 'mode', 'icon' => '👕', 'color' => 'rose'],
            ['name' => 'Équipements', 'slug' => 'equipements', 'icon' => '⚙️', 'color' => 'amber'],
            ['name' => 'Livres', 'slug' => 'livres', 'icon' => '📚', 'color' => 'blue'],
            ['name' => 'Autres', 'slug' => 'autres', 'icon' => '•••', 'color' => 'slate'],
        ];
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }

        .ak-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .ak-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .ak-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .ak-hide-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .ak-hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @keyframes ak-pulse-soft {
            0%, 100% {
                opacity: .65;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.08);
            }
        }

        .ak-pulse-soft {
            animation: ak-pulse-soft 2.5s ease-in-out infinite;
        }

        .ak-line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ak-line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <div class="bg-[#FAF9F6] text-slate-800">

        {{-- =========================================================
        1. BARRE DE CONFIANCE
        ========================================================== --}}
        <div class="bg-[#004D2A] text-white">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="min-h-[34px] flex items-center justify-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-[10px] sm:text-[11px] font-bold">
                        <span class="w-4 h-4 rounded-full bg-[#FCD116] text-[#004D2A] flex items-center justify-center text-[9px] font-black">
                            ✓
                        </span>
                        <span>Achetez et vendez sans stress</span>
                    </div>

                    <div class="hidden sm:flex items-center gap-5 text-[10px] text-emerald-100">
                        <span>🔒 Paiement sécurisé</span>
                        <span>🚚 Livraison interurbaine</span>
                        <span>📱 Mobile Money</span>
                    </div>
                </div>
            </div>
        </div>


        {{-- =========================================================
        2. HERO
        ========================================================== --}}
        <section class="relative overflow-hidden bg-gradient-to-b from-[#F0FAF4] via-white to-[#FAF9F6] border-b border-slate-200/70">
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#00843D]/5 pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-[#FCD116]/10 pointer-events-none"></div>

            <div class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200/80 px-3.5 py-1.5 text-xs font-black text-[#00843D]">
                            <span class="w-2 h-2 rounded-full bg-[#FCD116] ak-pulse-soft"></span>
                            Marketplace de confiance au Cameroun 🇨🇲
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-[1.12]">
                            Trouvez tout ce dont vous avez besoin, <span class="text-[#00843D]">livré sans stress.</span>
                        </h1>

                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl">
                            Profitez d'un système de paiement sécurisé par séquestre et Mobile Money. Votre argent est protégé jusqu'à la vérification de votre colis.
                        </p>

                        <form action="{{ route('buyer.home') }}" method="GET" class="max-w-xl">
                            <div class="flex items-center bg-white border-2 border-[#00843D]/30 rounded-2xl p-1.5 shadow-lg shadow-emerald-950/5 focus-within:border-[#00843D] focus-within:ring-4 focus-within:ring-emerald-100 transition-all">
                                <div class="pl-3 text-slate-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit, une marque, une catégorie..." autocomplete="off" class="w-full px-3 py-2 text-sm font-semibold text-slate-800 bg-transparent outline-none">
                                <button type="submit" class="shrink-0 bg-[#00843D] hover:bg-[#006B32] text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-black transition shadow-sm">
                                    Rechercher
                                </button>
                            </div>
                        </form>

                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <span class="text-xs font-bold text-slate-500">Populaires :</span>
                            @foreach(['Téléphonie', 'Informatique', 'Agriculture', 'Mode'] as $tag)
                                <a href="{{ route('buyer.home', ['q' => $tag]) }}" class="px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:border-[#00843D] hover:text-[#00843D] transition shadow-2xs">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-[#004D2A] via-[#006B32] to-[#00843D] text-white shadow-xl shadow-emerald-950/15 p-6 sm:p-8">
                            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full border-[20px] border-white/5 pointer-events-none"></div>
                            
                            <div class="relative z-10 space-y-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-[#FCD116] text-[10px] font-black uppercase tracking-wider">
                                    🔒 Garantie Séquestre
                                </span>
                                <h3 class="text-xl sm:text-2xl font-black leading-snug">
                                    Achetez l'esprit totalement tranquille.
                                </h3>
                                <p class="text-xs sm:text-sm text-emerald-50/90 leading-relaxed">
                                    Le paiement est conservé de façon sécurisée. Le vendeur ne reçoit ses fonds qu'après votre validation par code OTP à la réception.
                                </p>

                                <div class="pt-2 flex flex-wrap gap-2 text-xs font-bold">
                                    <span class="bg-white/10 px-3 py-1.5 rounded-lg">MTN MoMo</span>
                                    <span class="bg-white/10 px-3 py-1.5 rounded-lg">Orange Money</span>
                                    <span class="bg-white/10 px-3 py-1.5 rounded-lg">Colis Vérifié</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- =========================================================
        3. CATÉGORIES (Synchronisées & routées vers la recherche)
        ========================================================== --}}
        <section class="py-8 sm:py-10 bg-[#FAF9F6]">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4 mb-5">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.16em] text-[#00843D]">
                            Explorer
                        </p>
                        <h2 class="mt-1 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                            Qu'allez-vous acheter aujourd'hui ?
                        </h2>
                        <p class="mt-1 text-xs sm:text-sm text-slate-500">
                            Explorez les principales catégories de la marketplace.
                        </p>
                    </div>

                    <a href="{{ route('buyer.home') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-black text-[#00843D] hover:text-[#004D2A]">
                        Tout voir
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="flex overflow-x-auto gap-2.5 pb-2 ak-hide-scrollbar">
                    @foreach ($categoriesList as $cat)
                        @php
                            $isActive = request('category') === $cat['slug'] || request('q') === $cat['name'];

                            $colorClasses = match ($cat['color']) {
                                'amber' => 'bg-amber-50 text-amber-700 group-hover:bg-amber-100',
                                'orange' => 'bg-orange-50 text-orange-700 group-hover:bg-orange-100',
                                'blue' => 'bg-blue-50 text-blue-700 group-hover:bg-blue-100',
                                'rose' => 'bg-rose-50 text-rose-700 group-hover:bg-rose-100',
                                'slate' => 'bg-slate-100 text-slate-600 group-hover:bg-slate-200',
                                default => 'bg-emerald-50 text-[#00843D] group-hover:bg-emerald-100',
                            };
                        @endphp

                        {{-- Envoie la catégorie au clic (via le slug pour filtrer proprement) --}}
                        <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}#produits"
                            class="group shrink-0 w-[92px] sm:w-[105px] rounded-2xl bg-white border {{ $isActive ? 'border-[#00843D] ring-2 ring-emerald-100 shadow-sm' : 'border-slate-200' }} p-2.5 hover:border-[#00843D] hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">

                            <div class="flex justify-center">
                                <span class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center text-xl sm:text-2xl {{ $colorClasses }}">
                                    {{ $cat['icon'] }}
                                </span>
                            </div>

                            <p class="mt-2 text-[10px] sm:text-[11px] text-center font-extrabold text-slate-700 group-hover:text-[#00843D] ak-line-clamp-1">
                                {{ $cat['name'] }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- =========================================================
        4. CATALOGUE
        ========================================================== --}}
        <section id="produits" class="bg-white border-y border-slate-200/70 py-8 sm:py-10 scroll-mt-20">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#00843D]"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.15em] text-[#00843D]">
                                Marketplace
                            </span>
                        </div>

                        <h2 class="mt-1.5 text-xl sm:text-2xl font-black tracking-tight text-slate-900">
                            @if (request('q'))
                                Résultats pour <span class="text-[#00843D]">"{{ request('q') }}"</span>
                            @elseif(request('category'))
                                {{ ucfirst(request('category')) }}
                            @else
                                Produits disponibles
                            @endif
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ $products->total() }} {{ $products->total() > 1 ? 'produits disponibles' : 'produit disponible' }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="flex items-center gap-1.5 overflow-x-auto ak-hide-scrollbar">
                            <a href="{{ route('buyer.home', request()->except(['category', 'page'])) }}#produits"
                                class="shrink-0 px-3 py-1.5 rounded-full text-[10px] sm:text-[11px] font-black transition {{ !request('category') ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                                Tous
                            </a>

                            @foreach ([
                                'agriculture' => '🌿 Agriculture',
                                'elevage' => '🐄 Élevage',
                                'informatique' => '💻 Tech',
                                'mode' => '👕 Mode',
                            ] as $slug => $label)
                                <a href="{{ route('buyer.home', array_merge(request()->except(['category', 'page']), ['category' => $slug])) }}#produits"
                                    class="shrink-0 px-3 py-1.5 rounded-full text-[10px] sm:text-[11px] font-black transition {{ request('category') === $slug ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        <form method="GET" action="{{ route('buyer.home') }}" class="shrink-0">
                            @foreach(request()->except(['sort', 'page']) as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach

                            <select name="sort" onchange="this.form.submit()" class="w-full sm:w-auto bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-[11px] font-bold text-slate-700 outline-none focus:border-[#00843D] focus:ring-2 focus:ring-emerald-100 cursor-pointer">
                                <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Nouveautés</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Plus populaires</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                    @forelse ($products as $product)
                        @php
                            $availableStock = $product->availableStock();
                            $hasDiscount = $product->old_price && $product->old_price > $product->price;
                            $minQuantity = max(1, (int) ($product->min_quantity ?? 1));
                            $salesCount = (int) ($product->sales_count ?? 0);
                        @endphp

                        <a href="{{ route('product.show', ['product' => $product->id]) }}"
                            class="group min-w-0 bg-white rounded-xl overflow-hidden border border-slate-200 hover:border-[#00843D] hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">

                            <div class="relative aspect-[1/1.12] bg-slate-100 overflow-hidden">
                                @if ($product->images && $product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                @if ($availableStock <= 0 || $product->status === 'sold_out')
                                    <span class="absolute bottom-2 right-2 px-2 py-1 rounded-md bg-slate-950/85 text-white text-[9px] sm:text-[10px] font-black">
                                        Épuisé
                                    </span>
                                @elseif ($availableStock <= 5)
                                    <span class="absolute bottom-2 right-2 px-2 py-1 rounded-md bg-[#FCD116] text-slate-950 text-[9px] sm:text-[10px] font-black">
                                        {{ $availableStock }} restants
                                    </span>
                                @endif
                            </div>

                            <div class="p-2.5 sm:p-3 flex flex-col gap-1.5 flex-1">
                                <h3 class="text-[13px] sm:text-sm font-bold text-slate-900 leading-snug ak-line-clamp-2 group-hover:text-[#00843D] transition">
                                    {{ $product->title }}
                                </h3>

                                <div class="flex items-baseline flex-wrap gap-x-2 gap-y-0.5">
                                    <span class="inline-flex items-baseline gap-1">
                                        <span class="text-base sm:text-lg font-black text-[#00843D] leading-none">
                                            {{ number_format($product->minPrice(), 0, ',', ' ') }}
                                        </span>
                                        <span class="text-[10px] sm:text-[11px] font-black text-slate-500">FCFA</span>
                                    </span>

                                    @if ($hasDiscount)
                                        <span class="text-[11px] sm:text-xs font-bold text-[#CE1126] line-through decoration-[#CE1126]">
                                            {{ number_format($product->old_price, 0, ',', ' ') }}
                                        </span>
                                    @endif
                                </div>

                                @if ($product->shop)
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 min-w-0 text-[11px] sm:text-xs">
                                        <span class="font-extrabold text-slate-700 truncate">{{ $product->shop->name }}</span>
                                        <span class="text-slate-500 truncate">📍 {{ $product->city ?? ($product->shop->city ?? 'Cameroun') }}</span>
                                    </div>
                                @endif

                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] sm:text-xs pt-0.5">
                                    <span class="text-slate-500 font-semibold">{{ number_format($salesCount, 0, ',', ' ') }} vendus</span>
                                    <span class="text-[10px] font-black text-[#00843D] bg-emerald-50 px-2 py-0.5 rounded">Min. {{ $minQuantity }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-12">
                            <div class="max-w-lg mx-auto text-center">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-2xl">
                                    🔎
                                </div>
                                <h3 class="mt-4 text-base font-black text-slate-900">Aucun produit trouvé</h3>
                                <p class="mt-1 text-xs sm:text-sm text-slate-500">Essayez une autre recherche ou explorez toutes les catégories disponibles.</p>
                                <a href="{{ route('buyer.home') }}" class="mt-5 inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#00843D] hover:bg-[#006B32] text-white text-xs font-black transition">
                                    Voir tous les produits
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if (method_exists($products, 'hasPages') && $products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </section>

    </div>

@endsection