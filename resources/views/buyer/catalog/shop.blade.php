@extends('base')

@section('title', $shop->name ?? 'Boutique')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Banner & Header Boutique --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                {{-- Couverture / Bannière --}}
                <div class="h-32 sm:h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                    @if (isset($shop->banner_path))
                        <img src="{{ asset('storage/' . $shop->banner_path) }}" alt="Bannière {{ $shop->name }}"
                            class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Info Boutique --}}
                <div class="p-6 sm:p-8 relative pt-0 sm:pt-0">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between -mt-12 sm:-mt-16 gap-4">

                        {{-- Logo & Titre --}}
                        <div class="flex items-end gap-4">
                            <div
                                class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl bg-white p-1 shadow-md border border-gray-100 flex-shrink-0">
                                @if (isset($shop->logo_path))
                                    <img src="{{ asset('storage/' . $shop->logo_path) }}" alt="{{ $shop->name }}"
                                        class="w-full h-full object-cover rounded-xl">
                                @else
                                    <div
                                        class="w-full h-full bg-indigo-50 text-indigo-600 font-bold text-2xl flex items-center justify-center rounded-xl uppercase">
                                        {{ substr($shop->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="mb-1">
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $shop->name }}</h1>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 mr-1 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                        </svg>
                                        Vérifiée
                                    </span>
                                </div>
                                @if ($shop->description)
                                    <p class="text-sm text-gray-500 mt-1 max-w-2xl">{{ $shop->description }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Stats rapides --}}
                        <div
                            class="flex items-center gap-6 border-t sm:border-t-0 pt-4 sm:pt-0 w-full sm:w-auto justify-around sm:justify-start">
                            <div class="text-center sm:text-right">
                                <span class="block text-xl font-bold text-gray-900">{{ $products->total() }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wider">Produits</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Titre de la section --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Catalogue de la boutique</h2>
                <span class="text-sm text-gray-500">Affichage de
                    {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} sur {{ $products->total() }}
                    résultats</span>
            </div>

            {{-- Grille des produits --}}
            @if ($products->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                    <div
                        class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Aucun produit disponible</h3>
                    <p class="text-gray-500 text-sm">Cette boutique n'a pas encore mis de produits en ligne.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div
                            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">

                            {{-- Image du produit --}}
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @php
                                    $primaryImage = $product->images->first();
                                @endphp

                                @if ($primaryImage)
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-xs gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Aucun visuel</span>
                                    </div>
                                @endif

                                {{-- Overlay d'action rapide --}}
                                <div
                                    class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                    <a href="{{ route('product.show', $product->id) }}"
                                        class="bg-white text-gray-900 font-semibold px-4 py-2 rounded-xl text-sm shadow-lg hover:bg-gray-50 transition transform translate-y-2 group-hover:translate-y-0 duration-300">
                                        Voir le détail
                                    </a>
                                </div>
                            </div>

                            {{-- Détails du produit --}}
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3
                                        class="font-semibold text-gray-800 text-base line-clamp-2 hover:text-indigo-600 transition-colors mb-2">
                                        <a href="{{ route('product.show', $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-gray-400 block font-medium">Prix</span>
                                        <span class="text-lg font-extrabold text-indigo-600">
                                            {{ number_format($product->price, 0, ',', ' ') }} <span
                                                class="text-xs font-normal">FCFA</span>
                                        </span>
                                    </div>

                                    <a href="{{ route('product.show', $product->id) }}"
                                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination Tailwind --}}
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
