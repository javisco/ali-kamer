@extends('base')
@section('title', 'Noter votre commande')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-lg mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Votre avis</h1>
    <p class="text-sm text-gray-500 mb-6">Commande {{ $order->reference }}</p>

    <form method="POST" action="{{ route('buyer.reviews.store', $order) }}" class="space-y-6">
        @csrf

        {{-- Note produit --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Note du produit <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2" id="productStars">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating('product', {{ $i }})"
                            class="text-3xl text-gray-300 hover:text-yellow-400 transition star-product"
                            data-value="{{ $i }}">★</button>
                @endfor
            </div>
            <input type="hidden" name="product_rating" id="product_rating" required>
            @error('product_rating')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Note boutique --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Note de la boutique <strong>{{ $order->shop->name }}</strong>
                <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2" id="shopStars">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating('shop', {{ $i }})"
                            class="text-3xl text-gray-300 hover:text-yellow-400 transition star-shop"
                            data-value="{{ $i }}">★</button>
                @endfor
            </div>
            <input type="hidden" name="shop_rating" id="shop_rating" required>
            @error('shop_rating')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Commentaire --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Commentaire <span class="text-gray-400 font-normal">(optionnel)</span>
            </label>
            <textarea name="body" rows="4" maxlength="500"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-indigo-500"
                      placeholder="Partagez votre expérience...">{{ old('body') }}</textarea>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-4 rounded-2xl transition">
            Publier mon avis
        </button>
    </form>
</div>
</div>

@push('scripts')
<script>
    // Gestion des étoiles interactives
    function setRating(type, value) {
        document.getElementById(type + '_rating').value = value;
        document.querySelectorAll('.star-' + type).forEach(star => {
            star.classList.toggle('text-yellow-400', parseInt(star.dataset.value) <= value);
            star.classList.toggle('text-gray-300', parseInt(star.dataset.value) > value);
        });
    }
</script>
@endpush
@endsection