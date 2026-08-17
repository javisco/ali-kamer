@extends('base')

@section('title', 'Ali-Kamer — Produits et marketplace au Cameroun')

@section('content')

<div class="min-h-screen bg-slate-50/70 pb-12">

    {{-- =========================================================
        1. BANNIÈRE D'ACCUEIL & EN-TÊTE DE RECHERCHE
    ========================================================== --}}
    <section class="bg-white border-b border-slate-200/80 shadow-xs">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                {{-- Titre & Accroche --}}
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-orange-50 border border-orange-200/60 text-[9px] font-black uppercase tracking-wider text-orange-600">
                            🇨🇲 Marketplace Camerounaise Sécurisée
                        </span>
                    </div>
                    <h1 class="text-lg sm:text-2xl font-black tracking-tight text-slate-900">
                        Découvrez les meilleures affaires au <span class="text-orange-600">Cameroun</span>
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Achetez en toute confiance auprès de vendeurs certifiés avec protection de paiement Escrow.
                    </p>
                </div>

                {{-- Barre de recherche de la page d'accueil --}}
                <form method="GET" action="{{ route('buyer.home') }}" class="w-full lg:w-[480px] shrink-0">

                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('city'))
                        <input type="hidden" name="city" value="{{ request('city') }}">
                    @endif
                    @if (request('min_price'))
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    @endif
                    @if (request('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @endif
                    @if (request('shipping'))
                        <input type="hidden" name="shipping" value="{{ request('shipping') }}">
                    @endif

                    <div class="relative flex items-center">
                        <svg class="absolute left-3.5 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" />
                        </svg>

                        <input type="search" name="q" value="{{ request('q') }}"
                            placeholder="Rechercher un produit, une ville (Douala, Yaoundé...)..." autocomplete="off"
                            class="w-full h-11 pl-10 pr-28 rounded-2xl bg-slate-100/80 border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition shadow-xs">

                        <button type="submit"
                            class="absolute right-1 top-1 bottom-1 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold transition shadow-xs flex items-center justify-center">
                            Rechercher
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    {{-- =========================================================
        2. CONTENU PRINCIPAL DE LA PAGE
    ========================================================== --}}
    <div class="max-w-[1500px] mx-auto px-4 sm:px-6 pt-5">

        {{-- BARRE DE RAYONS & CATÉGORIES --}}
        <section class="mb-6">
            <div class="flex items-center justify-between mb-2.5">
                <div class="flex items-center gap-2">
                    <h2 class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wider">
                        Explorer les rayons
                    </h2>
                    <span class="hidden sm:inline-block text-[10px] text-slate-400 font-medium">
                        — Sélectionnez une catégorie
                    </span>
                </div>

                @if (request('category'))
                    <a href="{{ route('buyer.home') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Tout afficher &rarr;
                    </a>
                @endif
            </div>

            @php
                $categories = [
                    ['name' => 'Électronique', 'slug' => 'electronique', 'icon' => '💻'],
                    ['name' => 'Mode & Beauté', 'slug' => 'mode', 'icon' => '👕'],
                    ['name' => 'Maison & Bureau', 'slug' => 'maison', 'icon' => '🏠'],
                    ['name' => 'Agriculture', 'slug' => 'agriculture', 'icon' => '🌾'],
                ];
            @endphp

            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                @foreach ($categories as $category)
                    <a href="{{ route('buyer.home', ['category' => $category['slug']]) }}"
                        class="shrink-0 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-xs font-extrabold transition-all duration-200
                        {{ request('category') === $category['slug']
                            ? 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-200'
                            : 'bg-white border-slate-200 text-slate-700 hover:border-blue-400 hover:text-blue-600 shadow-2xs' }}">
                        
                        <span class="flex items-center justify-center w-5 h-5 rounded-md text-sm {{ request('category') === $category['slug'] ? 'bg-white/20' : 'bg-slate-100' }}">
                            {{ $category['icon'] }}
                        </span>
                        {{ $category['name'] }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- EN-TÊTE DU LISTING PRODUITS --}}
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200/80">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900">
                        @if (request('q'))
                            Résultats pour <span class="text-blue-600">"{{ request('q') }}"</span>
                        @elseif(request('category'))
                            Produits de la catégorie <span class="text-blue-600 uppercase">{{ request('category') }}</span>
                        @else
                            Offres & Produits Populaires
                        @endif
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-0.5 font-medium">
                        {{ $products->total() }} {{ Str::plural('article disponible', $products->total()) }}
                    </p>
                </div>

                @if (request('q') || request('category'))
                    <a href="{{ route('buyer.home') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:text-rose-600 hover:border-rose-200 transition shadow-2xs">
                        <span>Réinitialiser les filtres</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>

            {{-- GRILLE DE CARTES PRODUITS --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">

                @forelse($products as $product)
                    @php
                        $availableStock = $product->stock - $product->stock_reserved;
                        $hasDiscount = (method_exists($product, 'hasDiscount') && $product->hasDiscount()) 
                            || ($product->old_price && $product->old_price > $product->price);

                        $discountPercent = 0;
                        if ($hasDiscount && $product->old_price > 0) {
                            $discountPercent = round((($product->old_price - $product->price) / $product->old_price) * 100);
                        }
                    @endphp

                    <a href="{{ route('product.show', $product) }}"
                        class="group bg-white border border-slate-200/90 rounded-2xl overflow-hidden hover:border-blue-500/80 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">

                        <div>
                            {{-- Visuel Produit + Badges Flottants --}}
                            <div class="relative aspect-square bg-slate-100 overflow-hidden border-b border-slate-100">

                                @if ($product->images && $product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->title }}" loading="lazy"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[9px] font-bold mt-1 text-slate-400">Aucune photo</span>
                                    </div>
                                @endif

                                {{-- Badges Transport (Haut Gauche) --}}
                                @if ($product->shipping_included)
                                    <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-600/95 text-white text-[9px] font-black uppercase tracking-wider shadow-sm backdrop-blur-xs">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14m-6-6 6 6-6 6" />
                                        </svg>
                                        Transport inclus
                                    </span>
                                @else
                                    <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-bold shadow-sm backdrop-blur-xs">
                                        Transport non inclus
                                    </span>
                                @endif

                                {{-- Badge Promotion (Haut Droite) --}}
                                @if ($hasDiscount && $discountPercent > 0)
                                    <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded-md bg-red-600 text-white text-[9px] font-black shadow-sm">
                                        -{{ $discountPercent }}%
                                    </span>
                                @endif

                                {{-- Badges Stock (Bas Droite) --}}
                                @if ($availableStock <= 0 || $product->status === 'sold_out')
                                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-slate-900/90 text-white text-[9px] font-bold shadow-sm">
                                        Épuisé
                                    </span>
                                @elseif($availableStock <= 5)
                                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-amber-500 text-white text-[9px] font-extrabold shadow-sm">
                                        Reste {{ $availableStock }}
                                    </span>
                                @endif

                            </div>

                            {{-- Détails du Produit --}}
                            <div class="p-3 flex flex-col flex-1">

                                {{-- Nom de la boutique si chargée --}}
                                @if ($product->relationLoaded('shop') && $product->shop)
                                    <div class="flex items-center gap-1 mb-1">
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider truncate">
                                            {{ $product->shop->name }}
                                        </span>
                                        @if ($product->shop->verified_at)
                                            <svg class="w-3 h-3 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414 1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                @endif

                                {{-- Titre Produit --}}
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug line-clamp-2 min-h-[36px] group-hover:text-blue-600 transition-colors">
                                    {{ $product->title }}
                                </h3>

                                {{-- Localisation --}}
                                <div class="flex items-center gap-1 mt-1.5 text-[10px] font-medium text-slate-400">
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $product->city ?? 'Cameroun' }}</span>
                                </div>

                                {{-- Tarifs --}}
                                <div class="mt-3 pt-2 border-t border-slate-100">
                                    <div class="flex items-baseline gap-1.5 flex-wrap">
                                        <span class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                            {{ number_format($product->price, 0, ',', ' ') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-900">FCFA</span>

                                        @if ($hasDiscount)
                                            <span class="text-[10px] text-slate-400 line-through">
                                                {{ number_format($product->old_price, 0, ',', ' ') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                    </a>

                @empty

                    {{-- État Vide si aucun produit --}}
                    <div class="col-span-full">
                        <div class="bg-white border border-dashed border-slate-300 rounded-3xl py-12 px-6 text-center">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Aucun produit ne correspond à votre recherche</h3>
                            <p class="mt-1 text-xs text-slate-400">Essayez de modifier votre terme de recherche ou explorez une autre catégorie.</p>

                            @if (request('q') || request('category'))
                                <a href="{{ route('buyer.home') }}"
                                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md shadow-blue-200">
                                    Voir tous les produits
                                </a>
                            @endif
                        </div>
                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            @if ($products->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif

        </section>

        {{-- =========================================================
            3. SECTION RÉASSURANCE ET GARANTIE ESCROW
        ========================================================== --}}
        <section class="mt-12 mb-6">
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl overflow-hidden text-white p-6 sm:p-8 shadow-xl border border-slate-800 relative">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 relative z-10">

                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-400 bg-orange-500/10 border border-orange-500/20 px-2.5 py-1 rounded-full">
                            Garantie de Sécurité Ali-Kamer
                        </span>
                        <h2 class="text-lg sm:text-2xl font-black mt-2">
                            Achetez simplement. Payez en toute confiance.
                        </h2>
                        <p class="text-xs text-slate-300 mt-1 max-w-xl leading-relaxed">
                            Vos fonds restent sécurisés sur un compte séquestre bloqué et ne sont transférés au vendeur que lorsque vous confirmez la bonne réception de votre commande.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 lg:min-w-[480px]">
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-xs">
                            <div class="text-xl">🔐</div>
                            <p class="text-xs font-bold text-white mt-1">Paiement Protégé</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Mobile & Orange Money</p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-xs">
                            <div class="text-xl">📦</div>
                            <p class="text-xs font-bold text-white mt-1">Expédition Suivie</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Dans tout le Cameroun</p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-xs">
                            <div class="text-xl">🛡️</div>
                            <p class="text-xs font-bold text-white mt-1">Gestion Litiges</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Remboursement garanti</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>

</div>

@endsection