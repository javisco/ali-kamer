@extends('layouts.seller')

@section('title', 'Détails du produit - ALI-KAMER')

@section('content')
    {{-- Conteneur principal avec état Alpine.js pour la modale image --}}
    <div x-data="{ activeImage: null }" class="min-h-screen bg-[#F7F7F2] py-4 sm:py-6 px-3 sm:px-4">
        <div class="w-full max-w-2xl mx-auto">

            {{-- Carte Principale --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6 space-y-4">

                {{-- En-tête ALI-KAMER (Boutons ultra lisibles) --}}
                <div class="bg-[#016837] p-4 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-1.5 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#016837] border-2 border-white"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#E30613]"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#F9A01B]"></span>
                            <span class="text-[11px] font-black uppercase tracking-wider text-[#F9A01B] ml-1">Aperçu Vendeur</span>
                        </div>
                        <h1 class="text-lg sm:text-xl font-black text-white truncate max-w-xs sm:max-w-md">{{ $product->title }}</h1>
                    </div>

                    {{-- Boutons d'action à haut contraste --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('seller.products.edit', $product) }}"
                            class="bg-white hover:bg-gray-100 text-[#016837] font-extrabold text-xs px-3.5 py-2 rounded-lg transition-all shadow-sm flex items-center gap-1.5 border border-white">
                            <svg class="w-4 h-4 text-[#016837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Modifier</span>
                        </a>

                        <a href="{{ route('seller.products.index') }}"
                            class="bg-white hover:bg-red-50 text-[#E30613] font-bold text-xs px-3.5 py-2 rounded-lg transition-all shadow-sm border border-white">
                            Retour
                        </a>
                    </div>
                </div>

                {{-- 1. Galerie d'images interactive (Cliquer pour agrandir) --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold text-[#0a1b12] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-[#016837] rounded-full inline-block"></span>
                            Galerie Photos <span class="text-gray-400 font-normal lowercase">(cliquez pour agrandir)</span>
                        </h2>
                        <span class="text-[10px] font-black px-2 py-0.5 bg-[#016837]/10 text-[#016837] rounded-full">
                            {{ $product->images->count() }} photo(s)
                        </span>
                    </div>

                    @if ($product->images->count())
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 bg-[#F7F7F2]/60 p-2.5 rounded-xl border border-gray-200">
                            @foreach ($product->images as $index => $image)
                                <div @click="activeImage = '{{ Storage::url($image->url) }}'"
                                    class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 bg-white group cursor-pointer hover:border-[#016837] transition">
                                    <img src="{{ Storage::url($image->url) }}" alt="{{ $product->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">

                                    {{-- Couche d'interaction --}}
                                    <div class="absolute inset-0 bg-[#0a1b12]/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>

                                    @if ($loop->first)
                                        <span class="absolute top-1 left-1 bg-[#016837] text-white text-[8px] font-black px-1.5 py-0.5 rounded shadow-xs">
                                            Principale
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center bg-[#F7F7F2]/60 rounded-xl border border-dashed border-gray-300 text-gray-400 text-xs">
                            Aucune image enregistrée pour ce produit.
                        </div>
                    @endif
                </div>

                {{-- 2. Métriques clés --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <div class="bg-[#F7F7F2]/60 p-3 rounded-xl border border-gray-200">
                        <span class="block text-[11px] font-bold text-gray-500">Prix de vente</span>
                        <span class="block text-base font-black text-[#016837] mt-0.5">
                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                        </span>
                        @if ($product->old_price)
                            <span class="block text-[10px] text-gray-400 line-through">
                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                            </span>
                        @endif
                    </div>

                    <div class="bg-[#F7F7F2]/60 p-3 rounded-xl border border-gray-200">
                        <span class="block text-[11px] font-bold text-gray-500">Stock disponible</span>
                        <span class="block text-base font-black {{ $product->stock > 0 ? 'text-[#0a1b12]' : 'text-[#E30613]' }} mt-0.5">
                            {{ $product->stock }} <span class="text-[10px] font-normal text-gray-500">unité(s)</span>
                        </span>
                    </div>

                    <div class="bg-[#F7F7F2]/60 p-3 rounded-xl border border-gray-200">
                        <span class="block text-[11px] font-bold text-gray-500">Qté min. commande</span>
                        <span class="block text-base font-black text-[#0a1b12] mt-0.5">
                            {{ $product->min_quantity ?? 1 }}
                        </span>
                    </div>

                    <div class="bg-[#F7F7F2]/60 p-3 rounded-xl border border-gray-200">
                        <span class="block text-[11px] font-bold text-gray-500">Catégorie</span>
                        <span class="block text-xs font-bold text-[#0a1b12] mt-1 truncate">
                            {{ $product->category->name ?? 'Non spécifiée' }}
                        </span>
                    </div>
                </div>

                {{-- 3. Détails de livraison --}}
                <div class="bg-[#016837]/5 p-3.5 rounded-xl border border-[#016837]/20 space-y-1.5">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#016837]">Modalités de livraison</h3>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-[#0a1b12]">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold">Mode :</span>
                            @if ($product->shipping_included)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-[#016837] text-white">
                                    Transport inclus
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-[#E30613]/10 text-[#E30613]">
                                    Transport exclu
                                </span>
                            @endif
                        </div>

                        @if ($product->shipping_threshold_qty)
                            <div class="text-[11px] text-gray-600">
                                • Offert dès <strong class="text-[#016837] font-bold">{{ $product->shipping_threshold_qty }}</strong> article(s).
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 4. Description --}}
                <div class="space-y-1.5">
                    <h2 class="text-xs font-bold text-[#0a1b12] uppercase tracking-wider">Description du produit</h2>
                    <div class="bg-[#F7F7F2]/60 p-3.5 rounded-xl border border-gray-200 text-xs text-[#0a1b12] leading-relaxed whitespace-pre-line font-medium">
                        {{ $product->description }}
                    </div>
                </div>

            </div>
        </div>

        {{-- Modale d'agrandissement d'image (Zoom + Croix de fermeture) --}}
        <div x-show="activeImage" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="activeImage = null"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0a1b12]/80 backdrop-blur-xs" 
             style="display: none;">

            {{-- Fond cliquable pour fermer --}}
            <div class="absolute inset-0" @click="activeImage = null"></div>

            {{-- Conteneur de la photo agrandie --}}
            <div class="relative bg-white rounded-2xl p-2 max-w-xl w-full shadow-2xl z-10 border border-white/20">
                {{-- Bouton Croix (Fermer) --}}
                <button @click="activeImage = null" 
                        class="absolute -top-3 -right-3 bg-[#E30613] hover:bg-red-700 text-white rounded-full p-2 shadow-lg transition transform hover:scale-110 z-20 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="relative aspect-square w-full rounded-xl overflow-hidden bg-gray-100">
                    <img :src="activeImage" class="w-full h-full object-contain">
                </div>
            </div>
        </div>
    </div>
@endsection