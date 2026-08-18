@extends('base')
@section('title', 'Mes favoris')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mes favoris</h1>

    @if($wishlist->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="text-4xl mb-3">♡</div>
            <p class="text-gray-500 text-sm">Aucun favori pour l'instant.</p>
            <a href="{{ route('buyer.home') }}"
               class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
                Explorer le catalogue →
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($wishlist as $item)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                            overflow-hidden hover:shadow-md transition group">

                    {{-- Image --}}
                    <a href="{{ route('product.show', $item->product) }}"
                       class="block aspect-square bg-gray-50 overflow-hidden">
                        @if($item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                 alt="{{ $item->product->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition">
                        @endif
                    </a>

                    <div class="p-3">
                        <a href="{{ route('product.show', $item->product) }}"
                           class="block font-medium text-gray-900 text-sm line-clamp-2
                                  hover:text-indigo-600 transition">
                            {{ $item->product->title }}
                        </a>
                        <p class="text-indigo-600 font-bold text-sm mt-1">
                            @if($item->product->hasVariants())
                                À partir de {{ number_format($item->product->minPrice(), 0, ',', ' ') }} FCFA
                            @else
                                {{ number_format($item->product->price, 0, ',', ' ') }} FCFA
                            @endif
                        </p>

                        <div class="flex items-center justify-between mt-3 gap-2">
                            <a href="{{ route('product.show', $item->product) }}"
                               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white
                                      font-bold py-2 rounded-lg text-xs text-center transition">
                                Voir
                            </a>
                            <form method="POST"
                                  action="{{ route('buyer.wishlist.toggle', $item->product_id) }}">
                                @csrf
                                <button class="w-8 h-8 flex items-center justify-center
                                               border border-red-200 rounded-lg
                                               text-red-400 hover:bg-red-50 transition text-sm">
                                    ♥
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $wishlist->links() }}</div>
    @endif

</div>
</div>
@endsection