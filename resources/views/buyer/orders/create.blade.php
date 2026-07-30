@extends('base')

@section('title', 'Passer une commande')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Passer une commande</h1>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Résumé produit --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex gap-4">
                @if ($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->url) }}"
                        class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 line-clamp-2">{{ $product->title }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $product->shop->name }} · {{ $product->shop->city }}</p>
                    <p class="text-indigo-600 font-bold mt-1">
                        {{ number_format($product->price, 0, ',', ' ') }} FCFA / unité
                    </p>
                    <p class="text-xs mt-1 {{ $product->shipping_included ? 'text-emerald-600' : 'text-orange-500' }}">
                        {{ $product->shipping_included ? '✓ Transport inclus' : '⚠ Transport exclu — payé à l\'arrivée' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('buyer.orders.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Quantité --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4">Quantité</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="number" name="quantity" value="{{ old('quantity', $product->min_quantity) }}"
                                min="{{ $product->min_quantity }}" max="{{ $product->availableStock() }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-400 mt-1">
                                Minimum {{ $product->min_quantity }} — Stock disponible : {{ $product->availableStock() }}
                            </p>
                        </div>
                    </div>
                    @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Livraison --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4">Livraison</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ville de destination <span class="text-red-500">*</span>
                        </label>
                        <select name="destination_city" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Choisir votre ville --</option>
                            @foreach (['Douala', 'Yaoundé', 'Bafoussam', 'Bamenda', 'Buea', 'Limbé', 'Garoua', 'Maroua', 'Ngaoundéré', 'Bertoua', 'Ebolowa', 'Kribi'] as $city)
                                <option value="{{ $city }}"
                                    {{ old('destination_city') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (!$product->shipping_included)
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 mt-4 text-sm text-orange-700">
                            ⚠ Les frais de transport seront communiqués par le secrétaire de l'agence
                            et payables à l'arrivée de votre colis.
                        </div>
                    @endif
                </div>

                {{-- Paiement Mobile Money --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-1">Paiement Mobile Money</h2>
                    <p class="text-xs text-gray-500 mb-4">
                        Après validation, vous recevrez les instructions pour effectuer le transfert.
                    </p>

                    {{-- Opérateur --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opérateur</label>
                        <div class="flex gap-3">
                            <label
                                class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                                  {{ old('payer_operator') === 'mtn' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }}">
                                <input type="radio" name="payer_operator" value="mtn"
                                    {{ old('payer_operator') === 'mtn' ? 'checked' : '' }} required>
                                <span class="font-semibold text-yellow-700 text-sm">MTN MoMo</span>
                            </label>
                            <label
                                class="flex-1 flex items-center gap-3 border-2 rounded-xl p-3 cursor-pointer
                                  {{ old('payer_operator') === 'orange' ? 'border-orange-400 bg-orange-50' : 'border-gray-200' }}">
                                <input type="radio" name="payer_operator" value="orange"
                                    {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                                <span class="font-semibold text-orange-600 text-sm">Orange Money</span>
                            </label>
                        </div>
                        @error('payer_operator')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Numéro MoMo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Numéro Mobile Money <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <span
                                class="inline-flex items-center px-3 border border-r-0 border-gray-300
                                 rounded-l-xl bg-gray-50 text-gray-500 text-sm">+237</span>
                            <input type="tel" name="payer_phone"
                                value="{{ old('payer_phone', auth()->user()->phone_momo) }}" required
                                placeholder="655123456"
                                class="flex-1 border border-gray-300 rounded-r-xl px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500">
                        </div>
                        @error('payer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Note optionnelle --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Note pour le vendeur <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <textarea name="note" rows="3"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                             focus:ring-2 focus:ring-indigo-500"
                        placeholder="Ex: couleur souhaitée, instructions spéciales...">{{ old('note') }}</textarea>
                </div>

                {{-- Récapitulatif financier --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5">
                    <h2 class="font-bold text-gray-800 mb-3">Récapitulatif</h2>
                    <div class="space-y-2 text-sm" id="summary">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Prix produit</span>
                            <span class="font-medium" id="subtotal">— FCFA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de protection (2%)</span>
                            <span class="font-medium" id="protection">— FCFA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais Mobile Money (2%)</span>
                            <span class="font-medium" id="gateway">— FCFA</span>
                        </div>
                        <div class="border-t border-indigo-200 pt-2 mt-2 flex justify-between">
                            <span class="font-bold text-gray-900">Total à payer</span>
                            <span class="font-extrabold text-indigo-600 text-lg" id="total">— FCFA</span>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-4 rounded-2xl transition-colors shadow-sm text-base">
                    Confirmer la commande
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const price = {{ $product->price }};
            const qtyInput = document.querySelector('input[name="quantity"]');

            function format(n) {
                return new Intl.NumberFormat('fr-FR').format(n) + ' FCFA';
            }

            function updateSummary() {
                const qty = parseInt(qtyInput.value) || 1;
                const subtotal = price * qty;
                const protection = Math.round(subtotal * 0.02);
                const gateway = Math.round(subtotal * 0.02);
                const total = subtotal + protection + gateway;

                document.getElementById('subtotal').textContent = format(subtotal);
                document.getElementById('protection').textContent = format(protection);
                document.getElementById('gateway').textContent = format(gateway);
                document.getElementById('total').textContent = format(total);
            }

            qtyInput.addEventListener('input', updateSummary);
            updateSummary();
        </script>
    @endpush
@endsection
