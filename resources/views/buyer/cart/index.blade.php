@extends('layouts.buyer')

@section('title', 'Mon panier')

@section('content')

<div class="min-h-screen bg-[#F7F7F2] py-6 sm:py-8">
    <div class="max-w-4xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#016837]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#E30613]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F9A01B]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#F9A01B] ml-1">
                        Mon Panier
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0a1b12]">
                    Mon panier
                </h1>
            </div>

            <div class="w-10 h-10 rounded-xl bg-[#016837] text-[#F9A01B] flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Messages de Notification --}}
        @if(session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] px-4 py-3 rounded-2xl mb-4 text-sm font-bold flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] px-4 py-3 rounded-2xl mb-4 text-sm font-bold flex items-center gap-2">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        @if($cart->items->isEmpty())

            {{-- Panier vide --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 sm:p-12 text-center">
                <div class="mx-auto w-14 h-14 rounded-2xl bg-[#016837]/10 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-[#016837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>

                <h2 class="text-base font-bold text-[#0a1b12]">
                    Votre panier est vide
                </h2>

                <p class="text-gray-500 text-sm mt-1 mb-5">
                    Ajoutez des articles pour continuer vos achats.
                </p>

                <a href="{{ route('buyer.home') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#016837] hover:bg-[#0a542d] text-white font-bold text-sm transition shadow-sm">
                    Explorer le catalogue
                    <span class="text-[#F9A01B]">→</span>
                </a>
            </div>

        @else

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Liste des Articles --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-4 items-center">

                            {{-- Image Produit --}}
                            @if($item->product->images->first())
                                <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                     class="w-20 h-20 rounded-xl object-cover flex-shrink-0 bg-[#F7F7F2]">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-[#F7F7F2] flex-shrink-0 flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Informations --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#0a1b12] text-sm line-clamp-1">
                                    {{ $item->product->title }}
                                </p>

                                {{-- Badges & Variantes --}}
                                @if($item->variantLabel())
                                    <span class="inline-block text-[10px] font-black uppercase text-[#F9A01B] bg-[#F9A01B]/10 px-2 py-0.5 rounded-md mt-1">
                                        {{ $item->variantLabel() }}
                                    </span>
                                @endif

                                <p class="text-xs text-gray-400 mt-1 font-medium">
                                    Vendeur : <span class="text-gray-600">{{ $item->product->shop->name }}</span>
                                </p>

                                <div class="flex items-center justify-between mt-3 gap-2">
                                    {{-- Commande de Quantité --}}
                                    <form method="POST"
                                          action="{{ route('buyer.cart.update', $item) }}"
                                          class="flex items-center gap-1 bg-[#F7F7F2] p-1 rounded-xl border border-gray-200">
                                        @csrf @method('PATCH')
                                        <button type="submit" name="quantity"
                                                value="{{ max(0, $item->quantity - 1) }}"
                                                class="w-7 h-7 rounded-lg bg-white text-[#0a1b12] hover:bg-gray-100 text-sm font-black flex items-center justify-center transition shadow-xs">
                                            −
                                        </button>
                                        <span class="text-xs font-extrabold text-[#0a1b12] w-6 text-center">
                                            {{ $item->quantity }}
                                        </span>
                                        <button type="submit" name="quantity"
                                                value="{{ $item->quantity + 1 }}"
                                                class="w-7 h-7 rounded-lg bg-white text-[#0a1b12] hover:bg-gray-100 text-sm font-black flex items-center justify-center transition shadow-xs">
                                            +
                                        </button>
                                    </form>

                                    {{-- Prix & Suppression (Rouge) --}}
                                    <div class="flex items-center gap-3">
                                        <p class="font-black text-[#016837] text-sm">
                                            {{ number_format($item->subtotal(), 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                                        </p>
                                        <form method="POST" action="{{ route('buyer.cart.remove', $item) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-7 h-7 rounded-lg bg-[#E30613]/10 hover:bg-[#E30613] text-[#E30613] hover:text-white transition flex items-center justify-center" title="Supprimer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Résumé de la Commande --}}
                <div>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-4">
                        <h2 class="font-extrabold text-[#0a1b12] mb-4 flex items-center justify-between border-b border-gray-100 pb-3">
                            <span>Résumé</span>
                            <span class="text-xs bg-[#016837]/10 text-[#016837] px-2 py-0.5 rounded-full font-bold">
                                {{ $cart->itemsCount() }} article(s)
                            </span>
                        </h2>

                        <div class="space-y-2.5 text-sm mb-5">
                            <div class="flex justify-between text-gray-600 font-medium">
                                <span>Sous-total</span>
                                <span class="font-bold text-[#0a1b12]">{{ number_format($cart->total(), 0, ',', ' ') }} FCFA</span>
                            </div>

                            <div class="flex justify-between text-gray-500 text-xs">
                                <span class="flex items-center gap-1">
                                    Protection
                                    <span class="bg-[#F9A01B]/20 text-[#0a1b12] font-black px-1 rounded">2%</span>
                                </span>
                                <span class="font-semibold">+ {{ number_format($cart->total() * 0.02, 0, ',', ' ') }} FCFA</span>
                            </div>

                            <div class="flex justify-between text-gray-500 text-xs">
                                <span class="flex items-center gap-1">
                                    Frais MoMo
                                    <span class="bg-[#F9A01B]/20 text-[#0a1b12] font-black px-1 rounded">2%</span>
                                </span>
                                <span class="font-semibold">+ {{ number_format($cart->total() * 0.02, 0, ',', ' ') }} FCFA</span>
                            </div>

                            <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between items-center font-black text-[#0a1b12]">
                                <span class="text-sm">Total estimé</span>
                                <span class="text-[#016837] text-lg">
                                    {{ number_format($cart->total() * 1.04, 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                                </span>
                            </div>
                        </div>

                        {{-- Bouton d'action Vert avec flèche Jaune --}}
                        <a href="{{ route('buyer.cart.checkout') }}"
                           class="w-full bg-[#016837] hover:bg-[#0a542d] text-white font-extrabold py-3.5 rounded-xl text-center transition flex items-center justify-center gap-2 text-sm shadow-md hover:shadow-lg">
                            <span>Passer la commande</span>
                            <span class="text-[#F9A01B]">→</span>
                        </a>

                        <a href="{{ route('buyer.home') }}"
                           class="block text-center text-xs font-bold text-gray-400 hover:text-[#016837] transition mt-3">
                            Continuer mes achats
                        </a>
                    </div>
                </div>

            </div>

        @endif

    </div>
</div>

@endsection