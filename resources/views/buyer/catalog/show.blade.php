@extends('base')

@section('title', $product->title)

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                <a href="{{ route('buyer.home') }}" class="hover:text-indigo-600 transition">Accueil</a>
                <span>/</span>
                <a href="#" class="hover:text-indigo-600 transition">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="text-gray-700 font-medium line-clamp-1">{{ $product->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

                {{-- ── Galerie photos ─────────────────────────────────── --}}
                <div class="space-y-3">
                    {{-- Image principale --}}
                    <div class=" bg-white rounded-xl border border-gray-200 shadow-sm  aspect-video">
                        @if ($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->url) }}" alt="{{ $product->title }}"
                                class="w-full h-full object-cover object-center" id="mainImage">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Miniatures --}}
                    @if ($product->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            @foreach ($product->images as $image)
                                <button
                                    onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image->url) }}'"
                                    class="flex-shrink-0 w-16 h-16 rounded-xl border-2 border-gray-100
                                       hover:border-indigo-500 overflow-hidden transition-colors">
                                    <img src="{{ asset('storage/' . $image->url) }}" alt=""
                                        class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ── Informations produit ───────────────────────────── --}}
                <div class="flex flex-col gap-5">

                    {{-- Catégorie + Titre --}}
                    <div>
                        <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">
                            {{ $product->category->name }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1 leading-tight">
                            {{ $product->title }}
                        </h1>
                    </div>

                    {{-- Prix --}}
                    <div class="flex items-end gap-3">
                        <span class="text-3xl font-extrabold text-indigo-600">
                            {{ number_format($product->price, 0, ',', ' ') }}
                            <span class="text-base font-semibold">FCFA</span>
                        </span>
                        @if ($product->hasDiscount())
                            <span class="text-lg text-gray-400 line-through">
                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                            </span>
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full">
                                -{{ $product->discountPercent() }}%
                            </span>
                        @endif
                    </div>

                    {{-- Badges infos rapides --}}
                    <div class="flex flex-wrap gap-2">
                        {{-- Transport --}}
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                    {{ $product->shipping_included ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                            @if ($product->shipping_included)
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path
                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1v-1h2.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-5a1 1 0 00-.293-.707l-4-4A1 1 0 0015 3H4a1 1 0 00-1 1z" />
                                </svg>
                                Transport inclus
                            @else
                                ⚠ Transport exclu
                            @endif
                        </span>

                        {{-- Stock --}}
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                    {{ $product->availableStock() > 5 ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $product->availableStock() > 0 ? $product->availableStock() . ' en stock' : 'Rupture de stock' }}
                        </span>

                        {{-- Quantité min --}}
                        @if ($product->min_quantity > 1)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                Min. {{ $product->min_quantity }} unité(s)
                            </span>
                        @endif
                    </div>

                    {{-- Boutique --}}
                    <a href="{{ route('shop.show', $product->shop) }}"
                        class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-gray-100
                      shadow-sm hover:shadow-md hover:border-indigo-200 transition-all group">
                        <div
                            class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-lg
                            flex items-center justify-center uppercase flex-shrink-0">
                            {{ substr($product->shop->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition truncate">
                                {{ $product->shop->name }}
                            </p>
                            <p class="text-xs text-gray-400">{{ $product->shop->city }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 flex-shrink-0 transition"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 mt-auto">
                        @auth
                            @if ($product->availableStock() > 0)
                                <a href="{{ route('buyer.orders.create', $product->id) }}"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                                  py-3.5 px-6 rounded-2xl text-center transition-colors shadow-sm
                                  hover:shadow-indigo-200 hover:shadow-lg">
                                    Commander maintenant
                                </a>
                            @else
                                <button disabled
                                    class="flex-1 bg-gray-200 text-gray-400 font-bold py-3.5 px-6
                                       rounded-2xl text-center cursor-not-allowed">
                                    Rupture de stock
                                </button>
                            @endif

                            <a href="#"
                                class="flex items-center justify-center gap-2 border-2 border-indigo-100
                              hover:border-indigo-400 text-indigo-600 font-semibold py-3.5 px-6
                              rounded-2xl transition-colors bg-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Contacter
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                              py-3.5 px-6 rounded-2xl text-center transition-colors">
                                Connectez-vous pour commander
                            </a>
                        @endauth
                    </div>

                </div>
            </div>

            {{-- ── Description ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
            </div>

            {{-- ── Caractéristiques ────────────────────────────────────── --}}
            @if ($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Caractéristiques</h2>
                    <div class="divide-y divide-gray-50">
                        @foreach ($product->specifications as $key => $value)
                            <div
                                class="flex items-center py-3 {{ $loop->even ? 'bg-gray-50 -mx-4 px-4 rounded-lg' : '' }}">
                                <span class="w-1/3 text-sm font-medium text-gray-500">{{ $key }}</span>
                                <span class="flex-1 text-sm text-gray-900 font-semibold">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Autres produits du vendeur ──────────────────────────── --}}
            @if ($shopProducts->count())
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Autres produits de cette boutique</h2>
                        <a href="{{ route('shop.show', $product->shop) }}"
                            class="text-sm text-indigo-600 hover:underline font-medium">
                            Voir tout →
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($shopProducts as $item)
                            <a href="{{ route('product.show', $item) }}"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-sm
                              hover:shadow-md transition-all overflow-hidden">
                                <div class="aspect-square bg-gray-50 overflow-hidden">
                                    @if ($item->images->first())
                                        <img src="{{ asset('storage/' . $item->images->first()->url) }}"
                                            alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                </div>
                                <div class="p-3">
                                    <p class="text-xs font-medium text-gray-800 line-clamp-2 mb-1">{{ $item->title }}</p>
                                    <p class="text-sm font-bold text-indigo-600">
                                        {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Produits similaires ─────────────────────────────────── --}}
            @if ($related->count())
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Produits similaires</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($related as $item)
                            <a href="{{ route('product.show', $item) }}"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-sm
                              hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                                <div class="aspect-square bg-gray-50 overflow-hidden">
                                    @if ($item->images->first())
                                        <img src="{{ asset('storage/' . $item->images->first()->url) }}"
                                            alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <p class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2">{{ $item->title }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-indigo-600">
                                            {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                        </span>
                                        <span
                                            class="text-xs {{ $item->shipping_included ? 'text-emerald-500' : 'text-orange-400' }}">
                                            {{ $item->shipping_included ? '✓ Transport' : '⚠ Transport' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
