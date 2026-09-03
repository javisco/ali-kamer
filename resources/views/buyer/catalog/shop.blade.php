@extends('base')

@section('title', $shop->name ?? 'Boutique')

@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Banner & Header Boutique Ali-Kamer --}}
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden mb-8">

                {{-- Couverture / Bannière avec overlay aux couleurs Ali-Kamer --}}
                <div class="h-44 sm:h-64 bg-[#0a1b12] relative overflow-hidden">
                    @if (isset($shop->banner_path) || isset($shop->banner))
                        <img src="{{ Storage::url($shop->banner_path ?? $shop->banner) }}" alt="Bannière {{ $shop->name }}"
                            class="w-full h-full object-cover">
                        {{-- Filtre sombre subtil pour la lisibilité --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a1b12]/80 via-transparent to-transparent"></div>
                    @else
                        {{-- Motif d'arrière-plan par défaut aux teintes de la charte --}}
                        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-[#016837]/30 blur-3xl"></div>
                        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-[#F9A01B]/15 blur-3xl"></div>
                    @endif

                    {{-- Badge de statut sur la bannière --}}
                    <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                        @if ($shop->verified_at ?? true)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/90 backdrop-blur-md text-[#016837] shadow-md border border-white/20">
                                <svg class="w-4 h-4 text-[#016837] fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Boutique Vérifiée
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Corps de l'en-tête --}}
                <div class="px-6 sm:px-8 pb-6 relative">
                    <div class="flex flex-col lg:flex-row items-start lg:items-end justify-between -mt-14 sm:-mt-20 gap-6">

                        {{-- Logo & Titre --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-end gap-5 w-full lg:w-auto">

                            {{-- Logo avec contour relief --}}
                            <div class="relative group">
                                <div class="w-24 h-24 sm:w-36 sm:h-36 rounded-2xl sm:rounded-3xl bg-white p-1.5 shadow-2xl border border-slate-200 shrink-0">
                                    @if (isset($shop->logo) || isset($shop->logo_path))
                                        <img src="{{ Storage::url($shop->logo ?? $shop->logo_path) }}"
                                            alt="{{ $shop->name }}"
                                            class="w-full h-full object-cover rounded-xl sm:rounded-2xl">
                                    @else
                                        <div class="w-full h-full bg-[#016837] text-[#F9A01B] font-black text-3xl sm:text-4xl flex items-center justify-center rounded-xl sm:rounded-2xl uppercase tracking-wider shadow-inner">
                                            {{ substr($shop->name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Informations principales --}}
                            <div class="mb-1 space-y-1.5 flex-1">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight uppercase">
                                        {{ $shop->name }}
                                    </h1>
                                </div>

                                {{-- Localisation & Téléphone --}}
                                <div class="flex items-center gap-3 text-xs font-bold text-slate-600 flex-wrap pt-0.5">
                                    @if ($shop->city)
                                        <span class="flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-xl text-slate-700 border border-slate-200">
                                            <svg class="w-3.5 h-3.5 text-[#016837] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $shop->city }} {{ $shop->address ? '• ' . $shop->address : '' }}
                                        </span>
                                    @endif

                                    @if ($shop->phone)
                                        <span class="flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-xl text-slate-700 border border-slate-200">
                                            <svg class="w-3.5 h-3.5 text-[#016837] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $shop->phone }}
                                        </span>
                                    @endif
                                </div>

                                @if ($shop->description)
                                    <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-2xl leading-relaxed">
                                        {{ $shop->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Module Stats & Badges de confiance --}}
                        <div class="flex items-center gap-3 w-full lg:w-auto justify-between sm:justify-start border-t lg:border-t-0 border-slate-100 pt-4 lg:pt-0 mt-2 lg:mt-0">

                            {{-- Nombre de produits --}}
                            <div class="bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-2xl text-center min-w-[100px]">
                                <span class="block text-lg font-black text-slate-900 leading-tight">
                                    {{ method_exists($products, 'total') ? $products->total() : count($products) }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Articles
                                </span>
                            </div>

                            {{-- Badge Escrow Ali-Kamer --}}
                            <div class="bg-emerald-50/60 border border-emerald-100 px-4 py-2.5 rounded-2xl flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-[#016837] text-[#F9A01B] flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-black text-slate-900">Achat Sécurisé</span>
                                    <span class="text-[10px] font-bold text-[#016837]">Service Escrow Ali-Kamer</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Titre de la section --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#016837]"></span>
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-wider">Catalogue de la boutique</h2>
                </div>
                <span class="text-xs font-bold text-slate-500">
                    Affichage de {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} sur {{ $products->total() }} résultats
                </span>
            </div>

            {{-- Grille des produits --}}
            @if ($products->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Aucun produit disponible</h3>
                    <p class="text-slate-500 text-xs">Cette boutique n'a pas encore mis de produits en ligne.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">

                            {{-- Image du produit --}}
                            <div class="relative aspect-square bg-slate-100 overflow-hidden">
                                @php
                                    $primaryImage = $product->images->first();
                                @endphp

                                @if ($primaryImage)
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 text-xs gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>Aucun visuel</span>
                                    </div>
                                @endif

                                {{-- Overlay d'action rapide --}}
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                    <a href="{{ route('product.show', $product->id) }}"
                                        class="bg-white text-slate-900 font-bold px-4 py-2 rounded-xl text-xs shadow-lg hover:bg-[#F9A01B] hover:text-slate-900 transition transform translate-y-2 group-hover:translate-y-0 duration-300">
                                        Voir le détail
                                    </a>
                                </div>
                            </div>

                            {{-- Détails du produit --}}
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm line-clamp-2 hover:text-[#016837] transition-colors mb-2">
                                        <a href="{{ route('product.show', $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Prix</span>
                                        <span class="text-base font-black text-[#016837]">
                                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                        </span>
                                    </div>

                                    <a href="{{ route('product.show', $product->id) }}"
                                        class="w-9 h-9 rounded-xl bg-slate-100 text-[#016837] hover:bg-[#016837] hover:text-white transition-all flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection