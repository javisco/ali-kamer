@extends('layouts.buyer')

@section('title', 'Finaliser la commande')

@section('content')

<div class="min-h-screen bg-[#F7F7F2] py-6 sm:py-8">
    <div class="max-w-2xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#016837]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#E30613]"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#F9A01B]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#F9A01B] ml-1">
                        Paiement sécurisé
                    </span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0a1b12]">
                    Finaliser la commande
                </h1>
            </div>

            <div class="w-10 h-10 rounded-xl bg-[#016837] text-white flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>

        {{-- Erreurs (Rouge) --}}
        @if($errors->any())
            <div class="bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] px-4 py-3 rounded-2xl mb-6 text-sm font-medium">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <span>•</span> {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Récapitulatif articles --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="font-bold text-[#0a1b12] mb-4 flex items-center justify-between">
                <span>Articles ({{ $cart->itemsCount() }})</span>
                <span class="text-xs text-[#016837] font-semibold">Récapitulatif</span>
            </h2>

            <div class="divide-y divide-gray-50">
                @foreach($cart->items as $item)
                    <div class="flex items-center gap-3 py-3">
                        @if($item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                 class="w-12 h-12 rounded-xl object-cover flex-shrink-0 bg-[#F7F7F2]">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-[#F7F7F2] flex items-center justify-center text-gray-300 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-[#0a1b12] truncate">
                                {{ $item->product->title }}
                            </p>
                            @if($item->variantLabel())
                                <p class="text-xs font-semibold text-[#F9A01B]">{{ $item->variantLabel() }}</p>
                            @endif
                            <p class="text-xs text-gray-400 font-medium">Quantité : {{ $item->quantity }}</p>
                        </div>

                        <p class="font-extrabold text-sm text-[#016837] flex-shrink-0">
                            {{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('buyer.cart.order') }}" class="space-y-5">
            @csrf

            {{-- Ville de destination --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <label class="block text-sm font-bold text-[#0a1b12] mb-2">
                    Ville de livraison <span class="text-[#E30613]">*</span>
                </label>
                <select name="destination_city" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-[#0a1b12] font-medium bg-[#F7F7F2]/50 focus:bg-white focus:outline-none focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition">
                    <option value="">-- Choisir votre ville --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ old('destination_city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Paiement MoMo --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <h2 class="font-bold text-[#0a1b12]">Moyen de paiement</h2>

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition
                                  {{ old('payer_operator') === 'mtn' ? 'border-[#F9A01B] bg-[#F9A01B]/10' : 'border-gray-100 hover:border-gray-200' }}">
                        <input type="radio" name="payer_operator" value="mtn"
                               class="text-[#F9A01B] focus:ring-[#F9A01B]"
                               {{ old('payer_operator') === 'mtn' ? 'checked' : '' }} required>
                        <span class="font-bold text-[#0a1b12] text-sm">MTN MoMo</span>
                    </label>

                    <label class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition
                                  {{ old('payer_operator') === 'orange' ? 'border-[#E30613] bg-[#E30613]/10' : 'border-gray-100 hover:border-gray-200' }}">
                        <input type="radio" name="payer_operator" value="orange"
                               class="text-[#E30613] focus:ring-[#E30613]"
                               {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                        <span class="font-bold text-[#0a1b12] text-sm">Orange Money</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0a1b12] mb-2">
                        Numéro de téléphone <span class="text-[#E30613]">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 border border-r-0 border-gray-200 rounded-l-xl bg-[#F7F7F2] text-gray-600 font-bold text-sm">
                            +237
                        </span>
                        <input type="tel" name="payer_phone"
                               value="{{ old('payer_phone') }}"
                               required placeholder="655123456"
                               class="flex-1 border border-gray-200 rounded-r-xl px-4 py-3 text-sm text-[#0a1b12] font-medium bg-[#F7F7F2]/50 focus:bg-white focus:outline-none focus:border-[#016837] focus:ring-1 focus:ring-[#016837] transition">
                    </div>
                </div>
            </div>

            {{-- Récapitulatif Total (Style Vert/Jaune) --}}
            <div class="bg-[#016837]/5 border border-[#016837]/15 rounded-2xl p-5 space-y-2.5">
                <div class="flex justify-between text-sm text-gray-600 font-medium">
                    <span>Sous-total</span>
                    <span class="font-bold text-[#0a1b12]">{{ number_format($cart->total(), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600 font-medium">
                    <span class="flex items-center gap-1">
                        Protection + frais MoMo
                        <span class="text-[10px] bg-[#F9A01B] text-[#0a1b12] font-black px-1.5 py-0.5 rounded">4%</span>
                    </span>
                    <span class="font-bold text-[#0a1b12]">{{ number_format($cart->total() * 0.04, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="flex justify-between items-center font-extrabold text-[#0a1b12] border-t border-[#016837]/10 pt-3 mt-1">
                    <span class="text-base">Total à payer</span>
                    <span class="text-[#016837] text-xl font-black">
                        {{ number_format($cart->total() * 1.04, 0, ',', ' ') }} <span class="text-xs">FCFA</span>
                    </span>
                </div>
            </div>

            {{-- Bouton de validation (Vert principal avec accentuation Jaune) --}}
            <button type="submit"
                    class="w-full bg-[#016837] hover:bg-[#0a542d] text-white font-extrabold py-4 rounded-2xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-base">
                <span>Confirmer et payer</span>
                <span class="text-[#F9A01B] font-black">→</span>
            </button>
        </form>

    </div>
</div>

@endsection