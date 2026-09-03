@extends('base')

@section('title', 'Mes favoris')

@section('content')

<div class="min-h-screen bg-[#F7F7F2] py-6 sm:py-8">
    <div class="max-w-5xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#E30613]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#F9A01B]">
                        Ma sélection
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0a1b12]">
                    Mes favoris
                </h1>
            </div>

            <div class="w-10 h-10 rounded-xl bg-[#016837] text-white
                        flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 19.364 4.318 12.682a4.5 4.5 0 010-6.364z"/>
                </svg>
            </div>
        </div>

        @if($wishlist->isEmpty())

            {{-- Aucun favori --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                        p-10 sm:p-12 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-[#E30613]/10
                            flex items-center justify-center mb-4">
                    <span class="text-3xl text-[#E30613]">♡</span>
                </div>

                <h2 class="text-base font-bold text-[#0a1b12]">
                    Votre liste est vide
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Aucun favori pour l'instant.
                </p>

                <a href="{{ route('buyer.home') }}"
                   class="mt-5 inline-flex items-center gap-2 px-4 py-2.5
                          rounded-xl bg-[#016837] hover:bg-[#0a542d]
                          text-white font-bold text-sm transition shadow-sm">
                    Explorer le catalogue
                    <span>→</span>
                </a>
            </div>

        @else

            {{-- Grille des favoris --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">

                @foreach($wishlist as $item)

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                                overflow-hidden hover:shadow-md hover:-translate-y-0.5
                                transition duration-200 group">

                        {{-- Image --}}
                        <a href="{{ route('product.show', $item->product) }}"
                           class="block aspect-square bg-[#F7F7F2] overflow-hidden relative">

                            @if($item->product->images->first())

                                <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                     alt="{{ $item->product->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105
                                            transition duration-300">

                            @else

                                <div class="w-full h-full flex items-center justify-center
                                            text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                            @endif

                            {{-- Badge favori --}}
                            <div class="absolute top-2 right-2 w-7 h-7 rounded-lg
                                        bg-white/95 shadow-sm flex items-center justify-center
                                        text-[#E30613]">
                                ♥
                            </div>
                        </a>

                        <div class="p-3">

                            {{-- Nom --}}
                            <a href="{{ route('product.show', $item->product) }}"
                               class="block font-bold text-[#0a1b12] text-sm line-clamp-2
                                      hover:text-[#016837] transition leading-snug">
                                {{ $item->product->title }}
                            </a>

                            {{-- Prix --}}
                            <p class="text-[#016837] font-extrabold text-sm mt-2">
                                @if($item->product->hasVariants())
                                    <span class="text-[10px] font-bold text-gray-400 mr-1">
                                        À partir de
                                    </span>
                                    {{ number_format($item->product->minPrice(), 0, ',', ' ') }} FCFA
                                @else
                                    {{ number_format($item->product->price, 0, ',', ' ') }} FCFA
                                @endif
                            </p>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between mt-3 gap-2">

                                <a href="{{ route('product.show', $item->product) }}"
                                   class="flex-1 bg-[#016837] hover:bg-[#0a542d]
                                          text-white font-bold py-2 rounded-lg
                                          text-xs text-center transition">
                                    Voir
                                </a>

                                <form method="POST"
                                      action="{{ route('buyer.wishlist.toggle', $item->product_id) }}">
                                    @csrf

                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center
                                                   border border-[#E30613]/20 rounded-lg
                                                   text-[#E30613] bg-[#E30613]/5
                                                   hover:bg-[#E30613] hover:text-white
                                                   transition text-sm"
                                            title="Retirer des favoris">
                                        ♥
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-5">
                {{ $wishlist->links() }}
            </div>

        @endif

    </div>
</div>

@endsection