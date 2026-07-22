@extends('base')
@section('title','show')
@section('content')
    <div class="max-w-5xl mx-auto py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Galerie photos --}}
            <div>
                @if ($product->images->first())
                    <img src="{{ Storage::url($product->images->first()->url) }}"
                        class="w-full rounded-xl border border-gray-200 object-cover" id="mainImage">
                @endif
                <div class="flex gap-2 mt-3">
                    @foreach ($product->images as $image)
                        <img src="{{ Storage::url($image->url) }}"
                            class="w-16 h-16 rounded-lg border-2 border-gray-200 object-cover cursor-pointer
                                hover:border-blue-500"
                            onclick="document.getElementById('mainImage').src=this.src">
                    @endforeach
                </div>
            </div>

            {{-- Infos produit --}}
            <div>
                <p class="text-xs text-gray-400 mb-1">{{ $product->category->name }}</p>
                <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $product->title }}</h1>

                {{-- Prix --}}
                <div class="mb-4">
                    <span class="text-3xl font-bold text-blue-700">
                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                    </span>
                    @if ($product->hasDiscount())
                        <span class="text-gray-400 line-through ml-2 text-lg">
                            {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                        </span>
                        <span class="bg-red-100 text-red-600 text-xs font-medium px-2 py-1 rounded ml-2">
                            -{{ $product->discountPercent() }}%
                        </span>
                    @endif
                </div>

                {{-- Transport --}}
                <p class="text-sm mb-2 {{ $product->shipping_included ? 'text-green-600' : 'text-orange-500' }}">
                    {{ $product->shipping_included ? '✓ Transport inclus' : '⚠ Transport exclu — payé à l\'arrivée' }}
                </p>

                {{-- Stock --}}
                <p class="text-sm text-gray-500 mb-4">
                    Stock disponible : {{ $product->availableStock() }} unité(s)
                </p>

                {{-- Boutique --}}
                <div class="border border-gray-200 rounded-xl p-4 mb-4">
                    <p class="text-sm font-medium">{{ $product->shop->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->shop->city }}</p>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('shop.show', $product->shop) }}" class="text-xs text-blue-600 hover:underline">
                            Voir la boutique →
                        </a>
                        @auth
                            <a href="#" class="text-xs text-green-600 hover:underline">
                                Contacter le vendeur
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Bouton commander --}}
                @auth
                    <a href="#"
                        class="block w-full bg-blue-600 text-white text-center font-semibold
                          py-3 rounded-xl hover:bg-blue-700">
                        Commander maintenant
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="block w-full bg-blue-600 text-white text-center font-semibold
                          py-3 rounded-xl hover:bg-blue-700">
                        Connectez-vous pour commander
                    </a>
                @endauth
            </div>
        </div>

        {{-- Description --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold mb-3">Description</h2>
            <p class="text-gray-700 text-sm leading-relaxed">{{ $product->description }}</p>
        </div>

        {{-- Caractéristiques --}}
        @if ($product->specifications)
            <div class="mt-6">
                <h2 class="text-lg font-bold mb-3">Caractéristiques</h2>
                <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    @foreach ($product->specifications as $key => $value)
                        <tr class="border-b border-gray-100 {{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="px-4 py-2 font-medium text-gray-600">{{ $key }}</td>
                            <td class="px-4 py-2 text-gray-800">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

        {{-- Produits similaires --}}
        @if ($related->count())
            <div class="mt-10">
                <h2 class="text-lg font-bold mb-4">Produits similaires</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($related as $item)
                        <a href="{{ route('product.show', $item) }}"
                            class="bg-white rounded-xl border border-gray-200 p-3 hover:shadow-md transition">
                            @if ($item->images->first())
                                <img src="{{ Storage::url($item->images->first()->url) }}"
                                    class="w-full h-32 object-cover rounded-lg mb-2">
                            @endif
                            <p class="text-sm font-medium">{{ $item->title }}</p>
                            <p class="text-blue-700 font-bold text-sm mt-1">
                                {{ number_format($item->price, 0, ',', ' ') }} FCFA
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
