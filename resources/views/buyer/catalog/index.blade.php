@extends('base')
@section('title', 'index')
@section('content')
    <div class="max-w-6xl mx-auto py-6">

        {{-- Recherche --}}
        <form method="GET" action="{{ route('buyer.home') }}" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                        focus:ring-2 focus:ring-blue-500">
                <button class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm hover:bg-blue-700">
                    Rechercher
                </button>
            </div>
        </form>

        {{-- Grille produits --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($products as $product)
                <a href="{{ route('product.show', $product) }}"
                    class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">

                    {{-- Image --}}
                    @if ($product->images->first())
                        {{-- <img src="{{ str_starts_with($product->image?->url, 'http') ? $product->image?->url : Storage::url($product->image?->url) }}"> --}}
                        <img src="{{ Storage::url($product->images->first()->url) }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-300">
                            Pas d'image
                        </div>
                    @endif

                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $product->title }}</p>

                        <div class="mt-2">
                            <p class="text-blue-700 font-bold text-sm">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </p>
                            @if ($product->hasDiscount())
                                <p class="text-xs text-gray-400 line-through">
                                    {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                </p>
                            @endif
                        </div>

                        <p class="text-xs text-gray-400 mt-1">{{ $product->city }}</p>

                        <p class="text-xs mt-1 {{ $product->shipping_included ? 'text-green-600' : 'text-orange-500' }}">
                            {{ $product->shipping_included ? '✓ Transport inclus' : 'Transport exclu' }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-4 py-16 text-center text-gray-400">
                    Aucun produit trouvé.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
@endsection
