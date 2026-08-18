@extends('base')
@section('title', 'Finaliser la commande')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-2xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Finaliser la commande</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    {{-- Récapitulatif articles --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <h2 class="font-bold text-gray-800 mb-4">
            Articles ({{ $cart->itemsCount() }})
        </h2>
        @foreach($cart->items as $item)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                @if($item->product->images->first())
                    <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                         class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ $item->product->title }}
                    </p>
                    @if($item->variantLabel())
                        <p class="text-xs text-indigo-500">{{ $item->variantLabel() }}</p>
                    @endif
                    <p class="text-xs text-gray-400">× {{ $item->quantity }}</p>
                </div>
                <p class="font-bold text-sm text-gray-900 flex-shrink-0">
                    {{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA
                </p>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('buyer.cart.order') }}" class="space-y-5">
        @csrf

        {{-- Ville de destination --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Ville de livraison <span class="text-red-500">*</span>
            </label>
            <select name="destination_city" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                           focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Choisir votre ville --</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}"
                            {{ old('destination_city') === $city ? 'selected' : '' }}>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Paiement MoMo --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
            <h2 class="font-bold text-gray-800">Paiement Mobile Money</h2>

            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                              {{ old('payer_operator') === 'mtn' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }}">
                    <input type="radio" name="payer_operator" value="mtn"
                           {{ old('payer_operator') === 'mtn' ? 'checked' : '' }} required>
                    <span class="font-semibold text-yellow-700 text-sm">MTN MoMo</span>
                </label>
                <label class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                              {{ old('payer_operator') === 'orange' ? 'border-orange-400 bg-orange-50' : 'border-gray-200' }}">
                    <input type="radio" name="payer_operator" value="orange"
                           {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                    <span class="font-semibold text-orange-600 text-sm">Orange Money</span>
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Numéro MoMo <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 border border-r-0 border-gray-300
                                 rounded-l-xl bg-gray-50 text-gray-500 text-sm">+237</span>
                    <input type="tel" name="payer_phone"
                           value="{{ old('payer_phone') }}"
                           required placeholder="655123456"
                           class="flex-1 border border-gray-300 rounded-r-xl px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        {{-- Total --}}
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Sous-total</span>
                <span>{{ number_format($cart->total(), 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Protection + frais MoMo (4%)</span>
                <span>{{ number_format($cart->total() * 0.04, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 border-t border-indigo-200 pt-2 mt-2">
                <span>Total à payer</span>
                <span class="text-indigo-600 text-lg">
                    {{ number_format($cart->total() * 1.04, 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-4 rounded-2xl transition text-base">
            Confirmer et payer
        </button>
    </form>

</div>
</div>
@endsection