@extends('layouts.buyer')

@section('title', 'Finaliser la commande')

@section('content')

    <div class="min-h-screen bg-slate-50 py-6 sm:py-8">
        <div class="max-w-2xl mx-auto px-4">

            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-danger"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-accent-500"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-accent-500 ml-1">
                            Paiement sécurisé
                        </span>
                    </div>

                    <h1 class="text-2xl font-extrabold text-slate-900">
                        Finaliser la commande
                    </h1>
                </div>

                <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>

            {{-- Erreurs (Rouge) --}}
            @if ($errors->any())
                <div
                    class="bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-2xl mb-6 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center gap-2">
                            <span>•</span> {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            {{-- Récapitulatif articles, groupés par boutique --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
                <h2 class="font-bold text-slate-900 mb-4 flex items-center justify-between">
                    <span>Articles ({{ $cart->itemsCount() }})</span>
                    @if ($itemsByShop->count() > 1)
                        <span class="text-xs bg-accent-500/15 text-slate-900 font-bold px-2 py-1 rounded-lg">
                            {{ $itemsByShop->count() }} boutiques
                        </span>
                    @endif
                </h2>

                @foreach ($itemsByShop as $shopId => $shopItems)
                    <div class="mb-4 last:mb-0">
                        <p class="text-xs font-extrabold text-primary-600 uppercase tracking-wide mb-2">
                            {{ $shopItems->first()->product->shop->name }}
                        </p>

                        <div class="divide-y divide-slate-50 border border-slate-50 rounded-xl px-3">
                            @foreach ($shopItems as $item)
                                <div class="flex items-center gap-3 py-3">
                                    @if ($item->product->images->first())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->url) }}"
                                            class="w-12 h-12 rounded-xl object-cover flex-shrink-0 bg-slate-50">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-300 flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $item->product->title }}</p>
                                        @if ($item->variantLabel())
                                            <p class="text-xs font-semibold text-accent-500">{{ $item->variantLabel() }}</p>
                                        @endif
                                        <p class="text-xs text-slate-400 font-medium">Quantité : {{ $item->quantity }}</p>
                                    </div>

                                    <p class="font-extrabold text-sm text-primary-600 flex-shrink-0">
                                        {{ number_format($item->subtotal(), 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-right text-xs font-bold text-slate-500 mt-1.5">
                            Sous-total {{ $shopItems->first()->product->shop->name }} :
                            <span class="text-slate-900">
                                {{ number_format($shopItems->sum(fn($i) => $i->subtotal()), 0, ',', ' ') }} FCFA
                            </span>
                        </p>
                    </div>
                @endforeach

                @if ($itemsByShop->count() > 1)
                    <div
                        class="bg-primary-600/5 border border-primary-600/15 rounded-xl px-4 py-3 mt-3 text-xs text-slate-900 font-semibold flex items-start gap-2">
                        <span>ℹ️</span>
                        <span>
                            Vous achetez chez {{ $itemsByShop->count() }} boutiques différentes.
                            Un seul paiement Mobile Money couvre tout, mais chaque commande sera
                            préparée et livrée séparément par son vendeur.
                        </span>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('buyer.cart.order') }}" class="space-y-5">
                @csrf

                {{-- Ville de destination --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <label class="block text-sm font-bold text-slate-900 mb-2">
                        Ville de livraison <span class="text-danger">*</span>
                    </label>
                    <select name="destination_city" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 font-medium bg-slate-50/50 focus:bg-white focus:outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-500 transition">
                        <option value="">-- Choisir votre ville --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" {{ old('destination_city') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Paiement MoMo --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <h2 class="font-bold text-slate-900">Moyen de paiement</h2>

                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition
                                  {{ old('payer_operator', 'mtn') === 'mtn' ? 'border-accent-500 bg-accent-500/10' : 'border-slate-100 hover:border-slate-200' }}">
                            <input type="radio" name="payer_operator" value="mtn"
                                class="text-accent-500 focus:ring-accent-500"
                                {{ old('payer_operator', 'mtn') === 'mtn' ? 'checked' : '' }} required>
                            <span class="font-bold text-slate-900 text-sm">MTN MoMo</span>
                        </label>

                        <label
                            class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition
                                  {{ old('payer_operator') === 'orange' ? 'border-danger bg-danger/10' : 'border-slate-100 hover:border-slate-200' }}">
                            <input type="radio" name="payer_operator" value="orange"
                                class="text-danger focus:ring-danger"
                                {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                            <span class="font-bold text-slate-900 text-sm">Orange Money</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">
                            Numéro de téléphone <span class="text-danger">*</span>
                        </label>
                        <div class="flex">
                            <span
                                class="inline-flex items-center px-4 border border-r-0 border-slate-200 rounded-l-xl bg-slate-50 text-slate-600 font-bold text-sm">
                                +237
                            </span>
                            <input type="tel" name="payer_phone" value="{{ old('payer_phone', auth()->user()->phone_momo) }}" required
                                placeholder="655123456"
                                class="flex-1 border border-slate-200 rounded-r-xl px-4 py-3 text-sm text-slate-900 font-medium bg-slate-50/50 focus:bg-white focus:outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-500 transition">
                        </div>
                    </div>
                </div>

                {{-- Récapitulatif Total Dynamique --}}
                <div class="bg-primary-600/5 border border-primary-600/15 rounded-2xl p-5 space-y-2.5">
                    <div class="flex justify-between text-sm text-slate-600 font-medium">
                        <span>Sous-total</span>
                        <span class="font-bold text-slate-900" id="s-subtotal">—</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 font-medium">
                        <span>Frais de protection ({{ \App\Models\PlatformSetting::getValue('protection_rate') }}%)</span>
                        <span class="font-bold text-slate-900" id="s-protection">—</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 font-medium">
                        <span>Frais Mobile Money ({{ \App\Models\PlatformSetting::getValue('gateway_collect_rate') }}%)</span>
                        <span class="font-bold text-slate-900" id="s-elgiopay">—</span>
                    </div>

                    <div
                        class="flex justify-between items-center font-extrabold text-slate-900 border-t border-primary-600/10 pt-3 mt-1">
                        <span class="text-base">Total à payer</span>
                        <span class="text-primary-600 text-xl font-black" id="s-total">
                            — FCFA
                        </span>
                    </div>
                </div>

                {{-- Bouton de validation --}}
                <button type="submit"
                    class="w-full bg-accent-600 hover:bg-accent-700 text-white font-extrabold py-4 rounded-2xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-base cursor-pointer">
                    <span>Confirmer et payer</span>
                    <span class="text-accent-500 font-black">→</span>
                </button>
            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            const cartTotal = {{ $cart->total() }};
            const protRate = {{ \App\Models\PlatformSetting::getRate('protection_rate') }};
            const collectRate = {{ \App\Models\PlatformSetting::getRate('gateway_collect_rate') }};
            const fixedFee = {{ (int) \App\Models\PlatformSetting::getValue('gateway_fixed_fee', 0) }};

            function fmt(n) {
                return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
            }

            function updateCartSummary() {
                const subtotal = cartTotal;
                const protection = Math.ceil(subtotal * protRate);
                const wantNet = subtotal + protection;

                // Gross-Up collect
                const total = collectRate > 0 && collectRate < 1 ?
                    Math.ceil((wantNet + fixedFee) / (1 - collectRate)) :
                    wantNet + fixedFee;

                const elgiopay = total - wantNet;

                document.getElementById('s-subtotal').textContent = fmt(subtotal);
                document.getElementById('s-protection').textContent = fmt(protection);
                document.getElementById('s-elgiopay').textContent = fmt(elgiopay);
                document.getElementById('s-total').textContent = fmt(total);
            }

            document.addEventListener('DOMContentLoaded', updateCartSummary);
            updateCartSummary();
        </script>
    @endpush

@endsection