@extends('base')

@section('title', 'Passer une commande')

@section('content')
    <div class="bg-gray-50 min-h-screen py-6 flex items-center justify-center px-4">
        <div class="max-w-4xl w-full bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">

            {{-- En-tête principal --}}
            <div class="bg-indigo-600 px-8 py-5 text-white flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">Passer une commande</h1>
                    <p class="text-sm text-indigo-100 mt-0.5">Veuillez vérifier les détails avant de confirmer</p>
                </div>
                <span
                    class="text-xs font-bold uppercase tracking-wider bg-white/20 px-4 py-1.5 rounded-full border border-white/20 text-white">
                    Étape unique
                </span>
            </div>

            @if ($errors->any())
                <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-2xl text-sm">
                    <ul class="list-disc list-inside space-y-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('buyer.orders.store') }}" class="p-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- COLONNE GAUCHE (7/12) : Produit & Formulaire --}}
                    <div class="lg:col-span-7 space-y-6">

                        {{-- Carte Produit --}}
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-200 items-center">
                            @if ($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->url) }}"
                                    class="w-20 h-20 rounded-xl object-cover flex-shrink-0 border border-gray-200 shadow-sm">
                            @else
                                <div
                                    class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold">
                                    Pas d'image
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="font-bold text-gray-900 text-base line-clamp-1">{{ $product->title }}</h2>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $product->shop->name }} ·
                                    {{ $product->shop->city }}</p>
                                <p class="text-indigo-600 font-extrabold text-lg mt-1">
                                    {{ number_format($product->price, 0, ',', ' ') }} FCFA <span
                                        class="text-xs text-gray-500 font-normal">/ unité</span>
                                </p>
                            </div>
                        </div>

                        {{-- Quantité & Ville --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    Quantité <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="quantity" name="quantity"
                                    value="{{ old('quantity', $product->min_quantity) }}" min="{{ $product->min_quantity }}"
                                    max="{{ $product->availableStock() }}"
                                    class="w-full border-2 border-gray-300 focus:border-indigo-600 rounded-xl px-4 py-3 text-base font-bold text-gray-900 focus:ring-0 transition">
                                <p class="text-xs text-gray-500 font-medium mt-1">Min : {{ $product->min_quantity }} | En
                                    stock : {{ $product->availableStock() }}</p>
                            </div>

                            <div>
                                <label for="destination_city" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    Ville de destination <span class="text-red-500">*</span>
                                </label>
                                <select id="destination_city" name="destination_city" required
                                    class="w-full border-2 border-gray-300 focus:border-indigo-600 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:ring-0 transition">
                                    <option value="">-- Choisir une ville --</option>
                                    @forelse ($cities as $city)
                                        <option value="{{ $city }}"
                                            {{ old('destination_city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Aucune ville desservie pour le moment</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        {{-- Opérateur & Numéro --}}
                        <div class="border-t border-gray-200 pt-5">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Paiement Mobile Money <span
                                    class="text-red-500">*</span></label>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <label
                                    class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition hover:bg-yellow-50/60 has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                                    <input type="radio" name="payer_operator" value="mtn"
                                        class="w-4 h-4 text-yellow-600 focus:ring-yellow-500"
                                        {{ old('payer_operator', 'mtn') === 'mtn' ? 'checked' : '' }} required>
                                    <span class="font-extrabold text-yellow-800 text-sm">MTN MoMo</span>
                                </label>

                                <label
                                    class="flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer transition hover:bg-orange-50/60 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                                    <input type="radio" name="payer_operator" value="orange"
                                        class="w-4 h-4 text-orange-600 focus:ring-orange-500"
                                        {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                                    <span class="font-extrabold text-orange-700 text-sm">Orange Money</span>
                                </label>
                            </div>

                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-4 border-2 border-r-0 border-gray-300 rounded-l-xl bg-gray-100 text-gray-700 text-sm font-bold">+237</span>
                                <input type="tel" name="payer_phone"
                                    value="{{ old('payer_phone', auth()->user()->phone_momo) }}" required
                                    placeholder="655123456"
                                    class="flex-1 border-2 border-gray-300 focus:border-indigo-600 rounded-r-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-0 transition">
                            </div>
                        </div>

                        {{-- Note optionnelle --}}
                        <div>
                            <label for="note" class="block text-sm font-bold text-gray-800 mb-1">
                                Note pour le vendeur <span class="text-gray-400 font-normal">(Optionnel)</span>
                            </label>
                            <input type="text" id="note" name="note" value="{{ old('note') }}"
                                placeholder="Ex: Précision sur le modèle, la couleur..."
                                class="w-full border-2 border-gray-300 focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 focus:ring-0 transition">
                        </div>

                    </div>

                    {{-- COLONNE DROITE (5/12) : Récapitulatif Financier --}}
                    <div
                        class="lg:col-span-5 flex flex-col justify-between bg-indigo-50/80 rounded-2xl p-6 border-2 border-indigo-100">

                        {{-- Récapitulatif financier côté acheteur --}}
                        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5">
                            <h2 class="font-bold text-gray-800 mb-3">Récapitulatif</h2>
                            <div class="space-y-2 text-sm" id="summary">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Prix article</span>
                                    <span class="font-medium" id="s-subtotal">—</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">
                                        Frais de protection
                                        ({{ \App\Models\PlatformSetting::getValue('protection_rate') }}%)
                                    </span>
                                    <span id="s-protection">—</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">
                                        Frais Mobile Money
                                        ({{ \App\Models\PlatformSetting::getValue('gateway_collect_rate') }}%)
                                    </span>
                                    <span id="s-elgiopay">—</span>
                                </div>
                                <div
                                    class="flex justify-between font-bold text-gray-900
                    border-t border-indigo-200 pt-2 mt-2">
                                    <span>Total à payer</span>
                                    <span class="text-indigo-600 text-lg" id="s-total">—</span>
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                            <script>
                                const unitPrice = {{ $product->price }};
                                const protRate = {{ \App\Models\PlatformSetting::getRate('protection_rate') }};
                                const collectRate = {{ \App\Models\PlatformSetting::getRate('gateway_collect_rate') }};
                                const fixedFee = {{ (int) \App\Models\PlatformSetting::getValue('gateway_fixed_fee', 0) }};
                                const qtyInput = document.querySelector('input[name="quantity"]');

                                function fmt(n) {
                                    return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
                                }

                                function update() {
                                    const qty = parseInt(qtyInput.value) || 1;
                                    const subtotal = unitPrice * qty;
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

                                qtyInput.addEventListener('input', update);
                                update();
                            </script>
                        @endpush

                        {{-- Total final & Bouton de validation --}}
                        <div class="mt-6 pt-4 border-t-2 border-indigo-200/80">
                            <p class="text-xs uppercase font-bold text-indigo-900 tracking-wider mb-1">Total à payer</p>
                            <div class="text-3xl font-black text-indigo-700 mb-5 tracking-tight" id="total">
                                — FCFA
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-extrabold py-4 rounded-xl transition shadow-lg shadow-indigo-200 text-base flex items-center justify-center gap-2">
                                <span>Confirmer la commande</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>


@endsection
