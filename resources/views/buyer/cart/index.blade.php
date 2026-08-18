@extends('base')
@section('title', 'Mon panier')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mon panier</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($cart->items->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="text-4xl mb-3">🛒</div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">Panier vide</h2>
            <p class="text-gray-500 text-sm mb-4">Ajoutez des articles pour continuer.</p>
            <a href="{{ route('buyer.home') }}"
               class="inline-block bg-indigo-600 text-white font-bold px-6 py-2.5
                      rounded-xl hover:bg-indigo-700 transition text-sm">
                Explorer le catalogue
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Articles --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4
                                flex gap-4">

                        {{-- Image --}}
                        @if($item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                 class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex-shrink-0
                                        flex items-center justify-center text-gray-300 text-2xl">
                                📦
                            </div>
                        @endif

                        {{-- Infos --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm line-clamp-2">
                                {{ $item->product->title }}
                            </p>

                            {{-- Variante --}}
                            @if($item->variantLabel())
                                <p class="text-xs text-indigo-600 font-medium mt-0.5">
                                    {{ $item->variantLabel() }}
                                </p>
                            @endif

                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $item->product->shop->name }}
                            </p>

                            <div class="flex items-center justify-between mt-3">
                                {{-- Quantité --}}
                                <form method="POST"
                                      action="{{ route('buyer.cart.update', $item) }}"
                                      class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <button type="submit" name="quantity"
                                            value="{{ max(0, $item->quantity - 1) }}"
                                            class="w-7 h-7 rounded-lg border border-gray-300
                                                   text-gray-600 hover:bg-gray-50 text-sm font-bold">
                                        −
                                    </button>
                                    <span class="text-sm font-semibold w-6 text-center">
                                        {{ $item->quantity }}
                                    </span>
                                    <button type="submit" name="quantity"
                                            value="{{ $item->quantity + 1 }}"
                                            class="w-7 h-7 rounded-lg border border-gray-300
                                                   text-gray-600 hover:bg-gray-50 text-sm font-bold">
                                        +
                                    </button>
                                </form>

                                {{-- Prix et suppression --}}
                                <div class="flex items-center gap-3">
                                    <p class="font-bold text-indigo-600 text-sm">
                                        {{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA
                                    </p>
                                    <form method="POST"
                                          action="{{ route('buyer.cart.remove', $item) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-red-400 hover:text-red-600 text-xs">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Résumé --}}
            <div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-4">
                    <h2 class="font-bold text-gray-800 mb-4">Résumé</h2>

                    <div class="space-y-2 text-sm mb-5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">
                                {{ $cart->itemsCount() }} article(s)
                            </span>
                            <span>{{ number_format($cart->total(), 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Protection (2%)</span>
                            <span>+ {{ number_format($cart->total() * 0.02, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Frais MoMo (2%)</span>
                            <span>+ {{ number_format($cart->total() * 0.02, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="border-t border-gray-100 pt-2 mt-2 flex justify-between
                                    font-bold text-gray-900">
                            <span>Total estimé</span>
                            <span class="text-indigo-600">
                                {{ number_format($cart->total() * 1.04, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('buyer.cart.checkout') }}"
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white
                              font-bold py-3.5 rounded-xl text-center transition text-sm">
                        Passer la commande →
                    </a>

                    <a href="{{ route('buyer.home') }}"
                       class="block text-center text-sm text-gray-400 hover:underline mt-3">
                        Continuer mes achats
                    </a>
                </div>
            </div>

        </div>
    @endif

</div>
</div>
@endsection