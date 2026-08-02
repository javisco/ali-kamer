@extends('base')

@section('title', $product->title)

@section('content')
    <div class="bg-slate-50 min-h-screen py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Fil d'Ariane --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-400 mb-6 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('buyer.home') }}" class="hover:text-blue-600 transition-colors">Accueil</a>
                <span>/</span>
                <a href="#" class="hover:text-blue-600 transition-colors">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate">{{ $product->title }}</span>
            </nav>

            {{-- Section Principale Produit --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">

                {{-- ── 1. GALERIE PHOTOS (Image intégrale garantie) ──────────────── --}}
                <div class="lg:col-span-7 space-y-4">
                    {{-- Cadre Image Principale (object-contain + max-height pour visibilité totale) --}}
                    <div
                        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm h-[380px] sm:h-[480px] w-full relative flex items-center justify-center p-4">
                        @if ($product->images->first())
                            <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                id="mainImage"
                                class="max-w-full max-h-full object-contain hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium text-slate-400 mt-2">Aucune image disponible</span>
                            </div>
                        @endif

                        {{-- Badges flottants --}}
                        <div class="absolute top-4 left-4 flex flex-col gap-2 pointer-events-none z-10">
                            @if ($product->shipping_included)
                                <span
                                    class="bg-emerald-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-md uppercase tracking-wider">
                                    Transport inclus
                                </span>
                            @endif
                            @if ($product->hasDiscount())
                                <span
                                    class="bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md shadow-md">
                                    -{{ $product->discountPercent() }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Miniatures (Adaptées aussi en object-contain) --}}
                    @if ($product->images->count() > 1)
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none">
                            @foreach ($product->images as $index => $image)
                                <button type="button" onclick="changeMainImage('{{ Storage::url($image->url) }}', this)"
                                    class="thumb-btn flex-shrink-0 w-16 h-16 p-1 rounded-xl border-2 {{ $loop->first ? 'border-blue-600 shadow-sm' : 'border-slate-200' }} hover:border-blue-500 overflow-hidden bg-white transition-all duration-200 focus:outline-none flex items-center justify-center">
                                    <img src="{{ Storage::url($image->url) }}" alt=""
                                        class="max-w-full max-h-full object-contain">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── 2. BLOC D'ACHAT ET DÉTAILS ──────────────── --}}
                <div class="lg:col-span-5 flex flex-col justify-between space-y-6">

                    <div class="space-y-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        {{-- Catégorie + Titre --}}
                        <div>
                            <span
                                class="text-xs font-bold text-blue-600 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md">
                                {{ $product->category->name }}
                            </span>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-2 leading-snug">
                                {{ $product->title }}
                            </h1>
                        </div>

                        {{-- Prix --}}
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-baseline gap-3 flex-wrap">
                            <span class="text-3xl font-extrabold text-emerald-600">
                                {{ number_format($product->price, 0, ',', ' ') }} <span
                                    class="text-base font-bold">FCFA</span>
                            </span>
                            @if ($product->hasDiscount())
                                <span class="text-sm text-slate-400 line-through">
                                    {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                </span>
                            @endif
                        </div>

                        {{-- Stock & MOQ --}}
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 block font-medium">Disponibilité</span>
                                <span
                                    class="font-bold {{ $product->availableStock() > 0 ? 'text-slate-800' : 'text-red-600' }}">
                                    {{ $product->availableStock() > 0 ? $product->availableStock() . ' en stock' : 'Rupture' }}
                                </span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 block font-medium">Commande min.</span>
                                <span class="font-bold text-slate-800">
                                    {{ $product->min_quantity ?? 1 }} unité(s)
                                </span>
                            </div>
                        </div>

                        {{-- Boutique --}}
                        <a href="{{ route('shop.show', $product->shop) }}"
                            class="flex items-center gap-3.5 p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 hover:border-blue-400/80 hover:bg-white transition-all duration-200 group">
                            <div
                                class="w-11 h-11 rounded-lg bg-blue-600 text-white font-bold text-base flex items-center justify-center uppercase shadow-sm shrink-0">
                                {{ substr($product->shop->name, 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-xs text-slate-900 group-hover:text-blue-600 transition truncate">
                                    {{ $product->shop->name }}
                                </p>
                                <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $product->shop->city ?? 'Localisation non renseignée' }}</span>
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            @auth
                                @if ($product->availableStock() > 0)
                                    <a href="{{ route('buyer.orders.create', $product->id) }}"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-3.5 px-6 rounded-xl text-center text-xs transition-all duration-200 shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                                        <span>Commander maintenant</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <button disabled
                                        class="flex-1 bg-slate-200 text-slate-400 font-bold py-3.5 px-6 rounded-xl text-center text-xs cursor-not-allowed">
                                        Rupture de stock
                                    </button>
                                @endif

                                <a href="{{ route('messaging.start',['product'=>$product->id,'shop'=>$product->shop->id]) }}"
                                    class="flex items-center justify-center gap-2 border border-slate-300 hover:border-slate-400 text-slate-700 font-semibold py-3.5 px-5 rounded-xl text-xs transition-all bg-white hover:bg-slate-50">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Contacter
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl text-center text-xs transition-all shadow-md shadow-blue-500/20">
                                    Connectez-vous pour commander
                                </a>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── 3. DESCRIPTION ET CARACTÉRISTIQUES ───────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

                <div
                    class="{{ $product->specifications && is_array($product->specifications) && count($product->specifications) > 0 ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                        Description du produit
                    </h2>
                    <div class="text-slate-600 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>

                @if ($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                            Caractéristiques
                        </h2>
                        <div class="divide-y divide-slate-100">
                            @foreach ($product->specifications as $key => $value)
                                <div class="flex items-center justify-between py-2.5 text-xs">
                                    <span class="font-medium text-slate-500">{{ $key }}</span>
                                    <span class="font-semibold text-slate-800 text-right">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── 4. AUTRES PRODUITS DE LA BOUTIQUE ──────── --}}
            @if ($shopProducts->count())
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-base font-bold text-slate-900">
                            Autres produits de la boutique
                        </h2>
                        <a href="{{ route('shop.show', $product->shop) }}"
                            class="text-xs font-bold text-blue-600 hover:underline">
                            Voir tout →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                        @foreach ($shopProducts as $item)
                            <a href="{{ route('product.show', $item) }}"
                                class="group bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-400/60 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <div
                                        class="w-full h-48 bg-slate-50 overflow-hidden relative p-2 flex items-center justify-center">
                                        @if ($item->images->first())
                                            <img src="{{ Storage::url($item->images->first()->url) }}"
                                                alt="{{ $item->title }}"
                                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <h3
                                            class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-1">
                                            {{ $item->title }}
                                        </h3>
                                        <p class="text-sm font-extrabold text-emerald-600 mt-2">
                                            {{ number_format($item->price, 0, ',', ' ') }} <span
                                                class="text-[9px]">FCFA</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── 5. PRODUITS SIMILAIRES ──────────────────── --}}
            @if ($related->count())
                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-5">
                        Produits similaires
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                        @foreach ($related as $item)
                            <a href="{{ route('product.show', $item) }}"
                                class="group bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-blue-400/60 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <div
                                        class="w-full h-48 bg-slate-50 overflow-hidden relative p-2 flex items-center justify-center">
                                        @if ($item->images->first())
                                            <img src="{{ Storage::url($item->images->first()->url) }}"
                                                alt="{{ $item->title }}"
                                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                        @if ($item->shipping_included)
                                            <span
                                                class="absolute top-2 left-2 bg-emerald-600/90 text-white text-[9px] font-extrabold px-2 py-0.5 rounded uppercase">
                                                Transport inclus
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <h3
                                            class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-1">
                                            {{ $item->title }}
                                        </h3>
                                        <p class="text-sm font-extrabold text-emerald-600 mt-2">
                                            {{ number_format($item->price, 0, ',', ' ') }} <span
                                                class="text-[9px]">FCFA</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function changeMainImage(url, button) {
            document.getElementById('mainImage').src = url;

            document.querySelectorAll('.thumb-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'shadow-sm');
                btn.classList.add('border-slate-200');
            });
            button.classList.remove('border-slate-200');
            button.classList.add('border-blue-600', 'shadow-sm');
        }
    </script>
@endsection
