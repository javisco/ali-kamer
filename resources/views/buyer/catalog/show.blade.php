@extends('base')

@section('title', $product->title)

@section('content')
    <div class="bg-slate-50 min-h-screen py-6 sm:py-8" x-data="{
        lightboxOpen: false,
        lightboxImg: '{{ $product->images->first() ? Storage::url($product->images->first()->url) : '' }}',
        quantity: {{ $product->min_quantity ?? 1 }},
        minQty: {{ $product->min_quantity ?? 1 }},
        maxStock: {{ $product->availableStock() }},
        copied: false,
        shareUrl() {
            navigator.clipboard.writeText(window.location.href);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        }
    }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Fil d'Ariane --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-400 mb-5 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('buyer.home') }}" class="hover:text-blue-600 transition-colors">Accueil</a>
                <span>/</span>
                <a href="#" class="hover:text-blue-600 transition-colors">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate">{{ $product->title }}</span>
            </nav>

            {{-- Section Principale Produit : Équilibrée 50/50 (6-6) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mb-10 items-start">

                {{-- ── 1. GALERIE PHOTOS (6 Colonnes - Qualité originale préservée) ──────────────── --}}
                <div class="lg:col-span-6 space-y-3">
                    {{-- Cadre Image Principale --}}
                    <div
                        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm h-[340px] sm:h-[400px] w-full relative flex items-center justify-center p-3 group">
                        @if ($product->images->first())
                            <div @click="lightboxOpen = true" class="w-full h-full flex items-center justify-center cursor-zoom-in relative">
                                <img src="{{ Storage::url($product->images->first()->url) }}" alt="{{ $product->title }}"
                                    id="mainImage"
                                    class="max-w-full max-h-full object-contain rounded-xl hover:scale-102 transition-transform duration-300">
                                
                                {{-- Badge d'Agrandissement --}}
                                <span class="absolute bottom-3 right-3 bg-slate-900/80 hover:bg-slate-900 text-white text-[11px] font-medium px-2.5 py-1 rounded-lg backdrop-blur-md transition flex items-center gap-1.5 shadow-md opacity-80 group-hover:opacity-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                    </svg>
                                    <span>Plein écran</span>
                                </span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50 rounded-xl">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium text-slate-400 mt-2">Aucune image disponible</span>
                            </div>
                        @endif

                        {{-- Badges flottants --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none z-10">
                            @if ($product->shipping_included)
                                <span
                                    class="bg-emerald-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-md uppercase tracking-wider">
                                    Transport inclus
                                </span>
                            @endif
                            @if ($product->hasDiscount())
                                <span
                                    class="bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md shadow-md w-fit">
                                    -{{ $product->discountPercent() }}%
                                </span>
                            @endif
                        </div>

                        {{-- Actions Flottantes --}}
                        <div class="absolute top-3 right-3 z-20 flex items-center gap-2">
                            @auth
                                <form method="POST" action="{{ route('buyer.wishlist.toggle', $product->id) }}">
                                    @csrf
                                    <button type="submit" title="Ajouter aux favoris"
                                        class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md shadow-md border border-slate-100 flex items-center justify-center hover:scale-110 active:scale-95 transition-all">
                                        <svg class="w-4 h-4 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-slate-500 hover:text-red-500' }}"
                                            fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @endauth

                            @if(!$product->hasVariants())
                                <form method="POST" action="{{ route('buyer.cart.add', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" :value="quantity">
                                    <button type="submit" title="Ajouter au panier"
                                        class="w-8 h-8 rounded-full bg-indigo-600 text-white shadow-md flex items-center justify-center hover:bg-indigo-700 hover:scale-110 active:scale-95 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>

                    {{-- Miniatures --}}
                    @if ($product->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                            @foreach ($product->images as $index => $image)
                                <button type="button" @click="lightboxImg = '{{ Storage::url($image->url) }}'" onclick="changeMainImage('{{ Storage::url($image->url) }}', this)"
                                    class="thumb-btn flex-shrink-0 w-14 h-14 p-1 rounded-xl border-2 {{ $loop->first ? 'border-blue-600 shadow-sm' : 'border-slate-200' }} hover:border-blue-500 overflow-hidden bg-white transition-all duration-200 focus:outline-none flex items-center justify-center">
                                    <img src="{{ Storage::url($image->url) }}" alt=""
                                        class="w-full h-full object-contain rounded-lg">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── 2. BLOC D'ACHAT ET DÉTAILS (6 Colonnes - Compact) ──────────────── --}}
                <div class="lg:col-span-6 space-y-3">

                    <div class="space-y-3.5 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        {{-- Catégorie, Titre et Étoiles --}}
                        <div>
                            <span
                                class="text-[10px] font-bold text-blue-600 uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded">
                                {{ $product->category->name }}
                            </span>
                            <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1.5 leading-snug">
                                {{ $product->title }}
                            </h1>

                            {{-- Rappel Rapide des Avis --}}
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="flex text-yellow-400 text-xs">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= round($productRating) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <a href="#reviews-section" class="text-[11px] font-semibold text-slate-500 hover:text-blue-600 hover:underline">
                                    {{ number_format($productRating, 1) }} ({{ $reviews->count() }} avis)
                                </a>
                            </div>
                        </div>

                        {{-- Prix + Quantité (Disposition horizontale compacte) --}}
                        <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 flex-wrap">
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-extrabold text-emerald-600">
                                    {{ number_format($product->price, 0, ',', ' ') }} <span
                                        class="text-xs font-bold">FCFA</span>
                                </span>
                                @if ($product->hasDiscount())
                                    <span class="text-xs text-slate-400 line-through">
                                        {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                    </span>
                                @endif
                            </div>

                            @if ($product->availableStock() > 0)
                                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg p-1 shadow-sm">
                                    <button type="button" @click="if (quantity > minQty) quantity--"
                                        class="w-6 h-6 flex items-center justify-center font-bold text-xs text-slate-600 hover:bg-slate-100 rounded transition disabled:opacity-40"
                                        :disabled="quantity <= minQty">-</button>
                                    <span class="text-xs font-bold w-5 text-center text-slate-800" x-text="quantity"></span>
                                    <button type="button" @click="if (quantity < maxStock) quantity++"
                                        class="w-6 h-6 flex items-center justify-center font-bold text-xs text-slate-600 hover:bg-slate-100 rounded transition disabled:opacity-40"
                                        :disabled="quantity >= maxStock">+</button>
                                </div>
                            @endif
                        </div>

                        {{-- Variantes ou Bouton Panier --}}
                        @if ($product->hasVariants())
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl" x-data="variantSelector({{ json_encode($variantsData) }})">
                                @foreach ($product->attributes as $attribute)
                                    <div class="mb-2">
                                        <p class="text-[11px] font-semibold text-slate-700 mb-1">
                                            {{ $attribute->name }}
                                        </p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($attribute->values as $value)
                                                <button type="button"
                                                    @click="selectValue({{ $attribute->id }}, {{ $value->id }})"
                                                    :class="selectedValues[{{ $attribute->id }}] === {{ $value->id }} ?
                                                        'border-indigo-600 bg-indigo-50 text-indigo-700 font-semibold' :
                                                        'border-slate-300 bg-white text-slate-600 hover:border-indigo-400'"
                                                    class="px-2.5 py-1 border rounded-md text-xs transition">
                                                    {{ $value->value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div x-show="selectedVariant" class="bg-indigo-50/60 rounded-lg p-2 mb-2">
                                    <p class="text-base font-extrabold text-indigo-600"
                                        x-text="selectedVariant ? formatPrice(selectedVariant.price) + ' FCFA' : ''">
                                    </p>
                                    <p class="text-[10px] text-slate-500 mt-0.5"
                                        x-text="selectedVariant ? selectedVariant.stock + ' en stock' : ''">
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('buyer.cart.add', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" :value="quantity">
                                    <input type="hidden" name="variant_id" :value="selectedVariant?.id">
                                    <button :disabled="!selectedVariant || selectedVariant.stock === 0"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                                       py-2.5 rounded-xl transition text-xs shadow-md shadow-indigo-500/20 disabled:bg-slate-300
                                       disabled:cursor-not-allowed">
                                        <span x-show="!selectedVariant">Choisissez une variante</span>
                                        <span x-show="selectedVariant && selectedVariant.stock > 0">
                                            🛒 Ajouter au panier
                                        </span>
                                        <span x-show="selectedVariant && selectedVariant.stock === 0">
                                            Rupture de stock
                                        </span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('buyer.cart.add', $product) }}">
                                @csrf
                                <input type="hidden" name="quantity" :value="quantity">
                                <button
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                                   py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Ajouter au panier
                                </button>
                            </form>
                        @endif

                        {{-- Stock & MOQ --}}
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 block font-medium">Disponibilité</span>
                                <span
                                    class="font-bold {{ $product->availableStock() > 0 ? 'text-slate-800' : 'text-red-600' }}">
                                    {{ $product->availableStock() > 0 ? $product->availableStock() . ' en stock' : 'Rupture' }}
                                </span>
                            </div>

                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-slate-400 block font-medium">Commande min.</span>
                                <span class="font-bold text-slate-800">
                                    {{ $product->min_quantity ?? 1 }} unité(s)
                                </span>
                            </div>
                        </div>

                        {{-- Boutique --}}
                        <a href="{{ route('shop.show', $product->shop) }}"
                            class="flex items-center gap-2.5 p-2 bg-slate-50 rounded-xl border border-slate-200/80 hover:border-blue-400/80 hover:bg-white transition-all duration-200 group">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center uppercase shadow-sm shrink-0">
                                {{ substr($product->shop->name, 0, 2) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-xs text-slate-900 group-hover:text-blue-600 transition truncate">
                                    {{ $product->shop->name }}
                                </p>
                                <p class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $product->shop->city ?? 'Localisation non renseignée' }}</span>
                                </p>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        {{-- Actions Principales (Commander + Contacter côte à côte) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                            @auth
                                @if ($product->availableStock() > 0)
                                    <a href="{{ route('buyer.orders.create', $product->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-2.5 px-3 rounded-xl text-center text-xs transition-all duration-200 shadow-md shadow-blue-500/20 flex items-center justify-center gap-1.5">
                                        <span>Commander</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @else
                                    <button disabled
                                        class="bg-slate-200 text-slate-400 font-bold py-2.5 px-3 rounded-xl text-center text-xs cursor-not-allowed">
                                        Rupture
                                    </button>
                                @endif

                                <a href="{{ route('messaging.start', ['product' => $product->id, 'shop' => $product->shop->id]) }}"
                                    class="flex items-center justify-center gap-1.5 border border-slate-300 hover:border-slate-400 text-slate-700 font-semibold py-2.5 px-3 rounded-xl text-xs transition-all bg-white hover:bg-slate-50">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Contacter
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="col-span-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-center text-xs transition-all shadow-md shadow-blue-500/20">
                                    Connectez-vous pour commander
                                </a>
                            @endauth
                        </div>

                        {{-- Badges de Réassurance & Partage --}}
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[10px] text-slate-500 font-medium">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> Sécurisé</span>
                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Rapide</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($product->title . ' - ' . url()->current()) }}" target="_blank"
                                    class="p-1 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition" title="Partager sur WhatsApp">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                </a>
                                <button type="button" @click="shareUrl()"
                                    class="p-1 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200 transition relative" title="Copier le lien">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span x-show="copied" x-transition class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[9px] py-0.5 px-1 rounded shadow-md whitespace-nowrap">Copié !</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {{-- ── 3. DESCRIPTION ET CARACTÉRISTIQUES ───────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">

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

            @push('scripts')
                <script>
                    function variantSelector(variants) {
                        return {
                            variants,
                            selectedValues: {},
                            selectedVariant: null,

                            selectValue(attrId, valueId) {
                                this.selectedValues[attrId] = valueId;
                                this.updateVariant();
                            },

                            updateVariant() {
                                const selected = Object.values(this.selectedValues).sort();
                                this.selectedVariant = this.variants.find(v => {
                                    const ids = [...v.value_ids].sort();
                                    return JSON.stringify(ids) === JSON.stringify(selected.map(Number));
                                }) ?? null;
                            },

                            formatPrice(price) {
                                return new Intl.NumberFormat('fr-FR').format(price);
                            }
                        }
                    }
                </script>
            @endpush

            {{-- ── Avis clients ────────────────────────────────────────────── --}}
            <div id="reviews-section" class="mt-8">

                <div class="flex items-center gap-4 mb-6">
                    <div class="text-center">
                        <p class="text-5xl font-extrabold text-gray-900">
                            {{ $productRating ? number_format($productRating, 1) : '—' }}
                        </p>
                        <div class="text-yellow-400 text-xl mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= round($productRating) ? '★' : '☆' }}
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $reviews->count() }} avis
                        </p>
                    </div>

                    <div class="flex-1 space-y-1.5">
                        @for ($star = 5; $star >= 1; $star--)
                            @php
                                $count = $reviews->where('rating', $star)->count();
                                $pct = $reviews->count() > 0 ? ($count / $reviews->count()) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 w-3">{{ $star }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $pct }}%">
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 w-4">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                @if ($reviews->count())
                    <div class="space-y-4">
                        @foreach ($reviews as $review)
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">
                                            {{ $review->reviewer->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $review->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <div class="text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                </div>
                                @if ($review->body)
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $review->body }}
                                    </p>
                                @endif
                                <p class="text-xs text-emerald-500 mt-2">✓ Achat vérifié</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                        <p class="text-gray-400 text-sm">
                            Aucun avis pour l'instant. Soyez le premier à commander !
                        </p>
                    </div>
                @endif
            </div>

            {{-- ── Note de la boutique ─────────────────────────────────────── --}}
            @if ($shopRating)
                <div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-3">Note de la boutique</h2>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold
                        flex items-center justify-center uppercase text-sm flex-shrink-0">
                            {{ substr($product->shop->name, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $product->shop->name }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= round($shopRating) ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ number_format($shopRating, 1) }}/5
                                    — {{ $shopReviews->count() }} avis boutique
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── 4. AUTRES PRODUITS DE LA BOUTIQUE ──────── --}}
            @if ($shopProducts->count())
                <div class="mb-12 mt-12">
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
                                        class="w-full h-44 bg-slate-50 overflow-hidden relative p-2 flex items-center justify-center">
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
                                        class="w-full h-44 bg-slate-50 overflow-hidden relative p-2 flex items-center justify-center">
                                        @if ($item->images->first())
                                            <img src="{{ Storage::url($item->images->first()->url) }}"
                                                alt="{{ $item->title }}"
                                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                        @if ($item->shipping_included)
                                            <span
                                                class="absolute top-2 left-2 bg-emerald-600/90 text-white text-[9px] font-extrabold px-2 py-0.5 rounded uppercase z-10">
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

        {{-- ── MODALE LIGHTBOX (Agrandissement Photo) ──────────────── --}}
        <div x-show="lightboxOpen" x-transition.opacity
            class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4"
            style="display: none;">
            <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white hover:text-slate-300 p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img :src="lightboxImg" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
        </div>

        {{-- ── BARRE D'ACHAT FIXE MOBILE (Sticky Bottom Bar) ───────── --}}
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 p-3 z-40 shadow-lg flex items-center justify-between gap-3">
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold">Prix</p>
                <p class="text-base font-extrabold text-emerald-600">
                    {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                </p>
            </div>
            @auth
                @if ($product->availableStock() > 0)
                    <a href="{{ route('buyer.orders.create', $product->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition flex items-center gap-1.5 shadow-md shadow-blue-500/20">
                        <span>Commander</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @else
                    <button disabled class="bg-slate-200 text-slate-400 font-bold py-2 px-3 rounded-xl text-xs">
                        Rupture
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-xl text-xs">
                    Connexion
                </a>
            @endauth
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