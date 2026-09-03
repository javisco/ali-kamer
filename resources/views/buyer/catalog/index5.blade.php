@extends('base')

@section('title', 'Ali-Kamer — Achetez et vendez en toute confiance au Cameroun')

@section('content')

    <div class="min-h-screen bg-slate-50 text-slate-900">

        {{-- ================================================================
        HERO
    ================================================================= --}}
        <section class="relative overflow-hidden bg-slate-950 text-white">

            {{-- Décorations --}}
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute top-20 -right-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 w-80 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>

            {{-- Grille --}}
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px); background-size: 40px 40px;">
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center min-h-[620px] py-14 lg:py-20">

                    {{-- GAUCHE --}}
                    <div class="max-w-2xl">

                        {{-- Badge --}}
                        <div
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full
                                bg-white/5 border border-white/10 backdrop-blur-sm
                                text-xs font-semibold text-slate-200 mb-6">

                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                            </span>

                            Marketplace sécurisée au Cameroun
                        </div>

                        {{-- Titre --}}
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.05]">

                            Achetez.
                            <span class="text-blue-500">Vendez.</span>

                            <br>

                            <span class="text-orange-500">En toute</span>
                            <span class="text-emerald-400">confiance.</span>

                        </h1>

                        <p class="mt-6 text-base sm:text-lg text-slate-300 leading-relaxed max-w-xl">
                            Ali-Kamer sécurise vos achats en conservant votre paiement
                            jusqu'à ce que votre commande soit reçue et validée.
                        </p>

                        {{-- Recherche --}}
                        <form method="GET" action="{{ route('buyer.home') }}" class="mt-8">

                            <div
                                class="relative flex flex-col sm:flex-row gap-2
                                    p-2 bg-white rounded-2xl shadow-2xl shadow-black/30">

                                <div class="flex-1 relative">

                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>

                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Que recherchez-vous ?"
                                        class="w-full pl-11 pr-4 py-3.5
                                           bg-transparent border-0
                                           text-slate-900 text-sm
                                           placeholder-slate-400
                                           focus:ring-0 focus:outline-none">
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2
                                           bg-blue-600 hover:bg-blue-700
                                           text-white font-bold text-sm
                                           px-7 py-3.5 rounded-xl
                                           transition-all duration-200
                                           shadow-lg shadow-blue-600/20
                                           hover:shadow-blue-600/30
                                           active:scale-[.98]">

                                    Rechercher

                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>

                                </button>

                            </div>

                        </form>

                        {{-- Mini statistiques / confiance --}}
                        <div class="mt-8 flex flex-wrap gap-x-7 gap-y-4 text-xs">

                            <div class="flex items-center gap-2 text-slate-300">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-500/10
                                        border border-emerald-400/20
                                        flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>

                                <span>Paiement séquestre</span>
                            </div>

                            <div class="flex items-center gap-2 text-slate-300">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-500/10
                                        border border-blue-400/20
                                        flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </div>

                                <span>Livraison nationale</span>
                            </div>

                            <div class="flex items-center gap-2 text-slate-300">
                                <div
                                    class="w-8 h-8 rounded-lg bg-orange-500/10
                                        border border-orange-400/20
                                        flex items-center justify-center">
                                    <span class="text-sm">★</span>
                                </div>

                                <span>Vendeurs vérifiés</span>
                            </div>

                        </div>

                    </div>


                    {{-- DROITE : CARTE DE RÉASSURANCE --}}
                    <div class="hidden lg:block relative">

                        {{-- Carte arrière --}}
                        <div
                            class="absolute -top-5 -right-5 w-full h-full
                                rounded-[2rem]
                                bg-gradient-to-br from-blue-500/20 to-emerald-500/10
                                border border-white/5
                                rotate-3">
                        </div>

                        <div
                            class="relative bg-white/[0.07]
                                border border-white/10
                                backdrop-blur-xl
                                rounded-[2rem]
                                p-6 shadow-2xl">

                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-7">

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Votre transaction
                                    </p>

                                    <p class="text-lg font-bold mt-1">
                                        Protégée par Ali-Kamer
                                    </p>
                                </div>

                                <div
                                    class="w-11 h-11 rounded-xl
                                        bg-emerald-500/10
                                        border border-emerald-400/20
                                        flex items-center justify-center">

                                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>

                                </div>

                            </div>


                            {{-- Montant --}}
                            <div class="bg-slate-950/50 rounded-2xl p-5 border border-white/5">

                                <div class="flex justify-between items-center">

                                    <span class="text-xs text-slate-400">
                                        Paiement
                                    </span>

                                    <span class="text-xs text-emerald-400 font-semibold">
                                        ● Sécurisé
                                    </span>

                                </div>

                                <p class="text-3xl font-black mt-2">
                                    85 000
                                    <span class="text-sm text-slate-400 font-semibold">
                                        FCFA
                                    </span>
                                </p>

                            </div>


                            {{-- Progression --}}
                            <div class="mt-6 space-y-5">

                                <div class="flex gap-4">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-8 h-8 rounded-full
                                                bg-emerald-500/15
                                                border border-emerald-400/30
                                                flex items-center justify-center">

                                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>

                                        </div>

                                        <div class="w-px h-7 bg-white/10"></div>

                                    </div>

                                    <div class="pb-2">
                                        <p class="text-sm font-semibold">
                                            Paiement effectué
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            Votre argent est conservé en sécurité.
                                        </p>
                                    </div>

                                </div>


                                <div class="flex gap-4">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-8 h-8 rounded-full
                                                bg-blue-500/15
                                                border border-blue-400/30
                                                flex items-center justify-center">

                                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h14m-7-7l7 7-7 7" />
                                            </svg>

                                        </div>

                                        <div class="w-px h-7 bg-white/10"></div>

                                    </div>

                                    <div class="pb-2">
                                        <p class="text-sm font-semibold">
                                            Commande en livraison
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            Suivez l'acheminement de votre colis.
                                        </p>
                                    </div>

                                </div>


                                <div class="flex gap-4">

                                    <div
                                        class="w-8 h-8 rounded-full
                                            bg-white/5
                                            border border-white/10
                                            flex items-center justify-center">

                                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>

                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-slate-400">
                                            Réception validée
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            Le vendeur reçoit son paiement.
                                        </p>
                                    </div>

                                </div>

                            </div>


                            {{-- Footer --}}
                            <div
                                class="mt-7 pt-5 border-t border-white/10
                                    flex items-center gap-3">

                                <div
                                    class="w-9 h-9 rounded-lg bg-blue-500/10
                                        flex items-center justify-center">
                                    🔒
                                </div>

                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Votre paiement reste protégé jusqu'à
                                    la validation de votre commande.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>


        {{-- ================================================================
        CONTENU PRINCIPAL
    ================================================================= --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-14">


            {{-- ============================================================
            CATÉGORIES
        ============================================================= --}}
            <section>

                <div class="flex items-end justify-between mb-5">

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-1">
                            Découvrez
                        </p>

                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Explorez par catégorie
                        </h2>
                    </div>

                </div>


                @php
                    $categories = [
                        [
                            'name' => 'Électronique',
                            'icon' => '💻',
                            'slug' => 'electronique',
                            'description' => 'Téléphones, ordinateurs...',
                        ],
                        [
                            'name' => 'Mode & Beauté',
                            'icon' => '👕',
                            'slug' => 'mode',
                            'description' => 'Vêtements, accessoires...',
                        ],
                        [
                            'name' => 'Maison & Bureau',
                            'icon' => '🏠',
                            'slug' => 'maison',
                            'description' => 'Meubles, décoration...',
                        ],
                        [
                            'name' => 'Agriculture',
                            'icon' => '🌾',
                            'slug' => 'agriculture',
                            'description' => 'Produits agricoles...',
                        ],
                    ];
                @endphp


                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                    @foreach ($categories as $cat)
                        <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}"
                            class="group relative overflow-hidden
                              bg-white border border-slate-200
                              rounded-2xl p-5
                              hover:border-blue-300
                              hover:shadow-xl hover:shadow-blue-900/5
                              transition-all duration-300">

                            <div class="flex items-start justify-between">

                                <div
                                    class="w-12 h-12 rounded-xl
                                        bg-slate-100
                                        group-hover:bg-blue-50
                                        flex items-center justify-center
                                        text-2xl
                                        transition-colors">

                                    {{ $cat['icon'] }}

                                </div>

                                <svg class="w-5 h-5 text-slate-300
                                        group-hover:text-blue-500
                                        group-hover:translate-x-1
                                        transition-all"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />

                                </svg>

                            </div>

                            <h3
                                class="mt-4 text-sm font-bold text-slate-800
                                   group-hover:text-blue-600 transition-colors">

                                {{ $cat['name'] }}

                            </h3>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $cat['description'] }}
                            </p>

                        </a>
                    @endforeach

                </div>

            </section>


            {{-- ============================================================
            PRODUITS
        ============================================================= --}}
            <section>

                <div class="flex flex-col sm:flex-row sm:items-end
                        sm:justify-between gap-3 mb-6">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-1">
                            Marketplace
                        </p>

                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">

                            @if (request('q'))
                                Résultats pour
                                <span class="text-blue-600">
                                    "{{ request('q') }}"
                                </span>
                            @else
                                Produits populaires
                            @endif

                        </h2>

                    </div>


                    <div
                        class="inline-flex items-center self-start
                            bg-white border border-slate-200
                            rounded-full px-3.5 py-1.5">

                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>

                        <span class="text-xs font-semibold text-slate-600">
                            {{ $products->total() }}
                            {{ Str::plural('produit', $products->total()) }}
                        </span>

                    </div>

                </div>


                {{-- Produits --}}
                <div class="grid grid-cols-1 sm:grid-cols-2
                        lg:grid-cols-4 gap-5">

                    @forelse($products as $product)

                        <a href="{{ route('product.show', $product) }}"
                            class="group bg-white rounded-2xl
                              border border-slate-200
                              overflow-hidden
                              flex flex-col
                              shadow-sm
                              hover:shadow-2xl hover:shadow-slate-900/10
                              hover:-translate-y-1
                              transition-all duration-300">


                            {{-- IMAGE --}}
                            <div class="relative h-56 bg-slate-100 overflow-hidden">

                                @if ($product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->title }}"
                                        class="w-full h-full object-cover
                                           group-hover:scale-105
                                           transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full flex flex-col
                                            items-center justify-center
                                            text-slate-300">

                                        <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                        </svg>

                                        <span class="text-xs">
                                            Pas d'image
                                        </span>

                                    </div>
                                @endif


                                {{-- Badge transport --}}
                                @if ($product->shipping_included)
                                    <span
                                        class="absolute top-3 left-3
                                             bg-emerald-600 text-white
                                             text-[10px] font-bold
                                             px-2.5 py-1 rounded-lg
                                             shadow-lg">

                                        ✓ Transport inclus

                                    </span>
                                @else
                                    <span
                                        class="absolute top-3 left-3
                                             bg-slate-900/80 text-white
                                             text-[10px] font-bold
                                             px-2.5 py-1 rounded-lg
                                             backdrop-blur-md">

                                        Transport non inclus

                                    </span>
                                @endif

                            </div>


                            {{-- INFORMATIONS --}}
                            <div class="p-4 flex flex-col flex-1">

                                <h3
                                    class="font-bold text-sm text-slate-800
                                       group-hover:text-blue-600
                                       transition-colors line-clamp-2">

                                    {{ $product->title }}

                                </h3>


                                <div class="flex items-center gap-1 mt-2">

                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                                    </svg>

                                    <span class="text-xs text-slate-400 truncate">

                                        {{ $product->city ?? 'Ville non précisée' }}

                                    </span>

                                </div>


                                {{-- Prix --}}
                                <div class="mt-auto pt-4">

                                    @php
                                        $hasDiscount =
                                            (method_exists($product, 'hasDiscount') && $product->hasDiscount()) ||
                                            ($product->old_price && $product->old_price > $product->price);
                                    @endphp


                                    <div class="flex items-end justify-between gap-2">

                                        <div>

                                            <span class="text-lg font-black text-slate-900">

                                                {{ number_format($product->price, 0, ',', ' ') }}

                                                <span class="text-[10px] text-slate-500">
                                                    FCFA
                                                </span>

                                            </span>


                                            @if ($hasDiscount)
                                                <div class="flex items-center gap-2 mt-0.5">

                                                    <span class="text-[11px] text-slate-400 line-through">

                                                        {{ number_format($product->old_price, 0, ',', ' ') }}

                                                    </span>

                                                    @php
                                                        $discountPercent = round(
                                                            (($product->old_price - $product->price) /
                                                                $product->old_price) *
                                                                100,
                                                        );
                                                    @endphp

                                                    <span
                                                        class="text-[9px] font-black
                                                             text-red-600
                                                             bg-red-50
                                                             border border-red-100
                                                             px-1.5 py-0.5
                                                             rounded">

                                                        -{{ $discountPercent }}%

                                                    </span>

                                                </div>
                                            @endif

                                        </div>


                                        <div
                                            class="w-9 h-9 rounded-xl
                                                bg-blue-50
                                                group-hover:bg-blue-600
                                                flex items-center justify-center
                                                transition-colors">

                                            <svg class="w-4 h-4 text-blue-600
                                                    group-hover:text-white
                                                    transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />

                                            </svg>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div
                            class="col-span-full
                                bg-white rounded-3xl
                                border border-dashed border-slate-300
                                py-20 text-center">

                            <div
                                class="w-16 h-16 mx-auto mb-4
                                    bg-slate-100 rounded-2xl
                                    flex items-center justify-center">

                                <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                            </div>

                            <h3 class="font-bold text-slate-700">
                                Aucun produit trouvé
                            </h3>

                            <p class="text-sm text-slate-400 mt-1">
                                Essayez une autre recherche.
                            </p>

                            @if (request('q'))
                                <a href="{{ route('buyer.home') }}"
                                    class="inline-flex mt-5
                                      bg-blue-600 hover:bg-blue-700
                                      text-white text-xs font-bold
                                      px-5 py-2.5 rounded-xl">

                                    Voir tous les produits

                                </a>
                            @endif

                        </div>
                    @endforelse

                </div>


                {{-- Pagination --}}
                @if ($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </section>


            {{-- ============================================================
            BOUTIQUES
        ============================================================= --}}
            @if (isset($recommendedShops) && count($recommendedShops) > 0)

                <section>

                    <div class="flex items-end justify-between mb-5">

                        <div>

                            <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-1">
                                À découvrir
                            </p>

                            <h2 class="text-xl sm:text-2xl font-black">
                                Boutiques recommandées
                            </h2>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach ($recommendedShops as $shop)
                            <div
                                class="group bg-white
                                    border border-slate-200
                                    rounded-2xl p-4
                                    flex items-center justify-between
                                    hover:border-blue-300
                                    hover:shadow-lg
                                    transition-all">

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-12 h-12 rounded-xl
                                            bg-gradient-to-br
                                            from-blue-600 to-blue-700
                                            text-white font-black
                                            flex items-center justify-center
                                            shadow-lg shadow-blue-600/20">

                                        {{ strtoupper(substr($shop->name, 0, 2)) }}

                                    </div>

                                    <div>

                                        <h3 class="font-bold text-sm">
                                            {{ $shop->name }}
                                        </h3>

                                        <div class="flex items-center gap-3 mt-1">

                                            <span class="text-xs text-yellow-500 font-bold">
                                                ★ 4.9
                                            </span>

                                            <span class="text-slate-300">•</span>

                                            <span class="text-xs text-slate-500">
                                                {{ $shop->products_count ?? 0 }} produits
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                <a href="#"
                                    class="text-xs font-bold
                                      text-blue-600
                                      bg-blue-50
                                      hover:bg-blue-600
                                      hover:text-white
                                      border border-blue-100
                                      px-3 py-2 rounded-xl
                                      transition-colors">

                                    Boutique

                                </a>

                            </div>
                        @endforeach

                    </div>

                </section>

            @endif


            {{-- ============================================================
            COMMENT ÇA MARCHE
        ============================================================= --}}
            <section>

                <div class="text-center max-w-2xl mx-auto mb-8">

                    <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">
                        Simple et sécurisé
                    </p>

                    <h2 class="text-2xl sm:text-3xl font-black">
                        Acheter sur Ali-Kamer en 3 étapes
                    </h2>

                    <p class="text-sm text-slate-500 mt-3">
                        Nous avons conçu le parcours pour rendre vos transactions
                        simples, transparentes et sécurisées.
                    </p>

                </div>


                <div class="grid md:grid-cols-3 gap-5">

                    <div class="relative bg-white border border-slate-200
                            rounded-2xl p-6">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-xl
                                    bg-blue-50 text-blue-600
                                    flex items-center justify-center
                                    font-black">

                                01

                            </div>

                            <span class="text-3xl">🛍️</span>

                        </div>

                        <h3 class="font-bold mt-5">
                            Choisissez votre produit
                        </h3>

                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Parcourez les produits disponibles et choisissez
                            celui qui correspond à vos besoins.
                        </p>

                    </div>


                    <div class="relative bg-white border border-slate-200
                            rounded-2xl p-6">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-xl
                                    bg-emerald-50 text-emerald-600
                                    flex items-center justify-center
                                    font-black">

                                02

                            </div>

                            <span class="text-3xl">🔐</span>

                        </div>

                        <h3 class="font-bold mt-5">
                            Payez en toute sécurité
                        </h3>

                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Votre paiement est conservé en séquestre pendant
                            l'acheminement de votre commande.
                        </p>

                    </div>


                    <div class="relative bg-white border border-slate-200
                            rounded-2xl p-6">

                        <div class="flex items-center justify-between">

                            <div
                                class="w-11 h-11 rounded-xl
                                    bg-orange-50 text-orange-600
                                    flex items-center justify-center
                                    font-black">

                                03

                            </div>

                            <span class="text-3xl">📦</span>

                        </div>

                        <h3 class="font-bold mt-5">
                            Recevez et validez
                        </h3>

                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Vous recevez votre colis. Après validation,
                            le paiement est libéré au vendeur.
                        </p>

                    </div>

                </div>

            </section>


            {{-- ============================================================
            BANDEAU CONFIANCE
        ============================================================= --}}
            <section
                class="relative overflow-hidden
                        bg-slate-950
                        rounded-[2rem]
                        text-white">

                <div
                    class="absolute -right-20 -top-20
                        w-72 h-72
                        bg-blue-600/20
                        rounded-full blur-3xl">
                </div>

                <div class="relative p-8 sm:p-10 lg:p-12">

                    <div class="max-w-3xl">

                        <span
                            class="inline-flex items-center gap-2
                                 text-xs font-bold
                                 text-emerald-400
                                 uppercase tracking-widest">

                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                            Votre sécurité d'abord

                        </span>

                        <h2
                            class="text-2xl sm:text-3xl
                               lg:text-4xl
                               font-black mt-3">

                            Achetez sans avoir à faire
                            <span class="text-blue-400">
                                confiance à l'aveugle.
                            </span>

                        </h2>

                        <p
                            class="text-sm text-slate-400
                              max-w-2xl
                              leading-relaxed mt-4">

                            Ali-Kamer sépare le paiement de la livraison :
                            votre argent reste protégé jusqu'à ce que
                            votre commande soit reçue et validée.

                        </p>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-3
                            gap-4 mt-9">

                        <div class="bg-white/5 border border-white/10
                                rounded-2xl p-4">

                            <div class="text-xl mb-3">
                                🔒
                            </div>

                            <p class="text-sm font-bold">
                                Paiement protégé
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Votre argent reste sécurisé.
                            </p>

                        </div>


                        <div class="bg-white/5 border border-white/10
                                rounded-2xl p-4">

                            <div class="text-xl mb-3">
                                🚚
                            </div>

                            <p class="text-sm font-bold">
                                Livraison nationale
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Acheminement entre les villes.
                            </p>

                        </div>


                        <div class="bg-white/5 border border-white/10
                                rounded-2xl p-4">

                            <div class="text-xl mb-3">
                                🛡️
                            </div>

                            <p class="text-sm font-bold">
                                Protection acheteur
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Un système pensé pour vos transactions.
                            </p>

                        </div>

                    </div>

                </div>

            </section>


            {{-- ============================================================
            CTA VENDEUR
        ============================================================= --}}
            <section
                class="bg-gradient-to-r
                        from-blue-600 to-blue-700
                        rounded-[2rem]
                        p-8 sm:p-10
                        text-white
                        relative overflow-hidden">

                <div
                    class="absolute right-0 top-0
                        w-64 h-64
                        bg-white/10 rounded-full
                        blur-2xl">
                </div>

                <div
                    class="relative flex flex-col
                        md:flex-row
                        md:items-center
                        md:justify-between
                        gap-7">

                    <div>

                        <p
                            class="text-xs uppercase
                              tracking-widest
                              font-bold text-blue-200">

                            Vous êtes vendeur ?

                        </p>

                        <h2 class="text-2xl sm:text-3xl
                               font-black mt-2">

                            Développez votre activité
                            avec Ali-Kamer.

                        </h2>

                        <p class="text-sm text-blue-100
                              mt-2 max-w-xl">

                            Présentez vos produits à de nouveaux clients
                            et profitez d'un système de paiement conçu
                            pour rassurer acheteurs et vendeurs.

                        </p>

                    </div>


                    <a href="#"
                        class="shrink-0 inline-flex items-center
                          justify-center gap-2
                          bg-white text-blue-700
                          hover:bg-blue-50
                          font-bold text-sm
                          px-6 py-3.5
                          rounded-xl
                          shadow-lg
                          transition-colors">

                        Devenir vendeur

                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />

                        </svg>

                    </a>

                </div>

            </section>

        </div>

    </div>

@endsection
