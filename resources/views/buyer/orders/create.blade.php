@extends('base')

@section('title', 'Passer une commande')

@section('content')
    <div class="bg-slate-50 min-h-screen py-8 px-4 flex items-center justify-center">
        <div class="max-w-4xl w-full bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">

            {{-- En-tête principal --}}
            <div class="bg-[#0a1b12] px-6 sm:px-8 py-5 text-white flex justify-between items-center border-b border-emerald-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-[#F9A01B] animate-pulse"></div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight uppercase">Passer une commande</h1>
                        <p class="text-xs text-emerald-400 mt-0.5">Vérifiez les détails de votre achat avant la confirmation</p>
                    </div>
                </div>
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-[#F9A01B]/20 text-[#F9A01B] px-3.5 py-1.5 rounded-xl border border-[#F9A01B]/30">
                    Paiement Sécurisé
                </span>
            </div>

            @if ($errors->any())
                <div class="mx-6 sm:mx-8 mt-6 bg-red-50 border border-red-200 text-[#E30613] px-5 py-3.5 rounded-2xl text-xs font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('buyer.orders.store') }}" class="p-6 sm:p-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- COLONNE GAUCHE (7/12) : Produit & Formulaire --}}
                    <div class="lg:col-span-7 space-y-6">

                        {{-- Carte Produit --}}
                        <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 items-center">
                            @if ($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->url) }}"
                                    class="w-20 h-20 rounded-xl object-cover shrink-0 border border-slate-200 shadow-xs">
                            @else
                                <div class="w-20 h-20 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-semibold shrink-0">
                                    Pas d'image
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-1">{{ $product->title }}</h2>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $product->shop->name }} · {{ $product->shop->city }}</p>
                                <p class="text-[#016837] font-black text-lg mt-1">
                                    {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                    <span class="text-[11px] text-slate-400 font-normal">/ unité</span>
                                </p>
                            </div>
                        </div>

                        {{-- Quantité & Ville --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="quantity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Quantité <span class="text-[#E30613]">*</span>
                                </label>
                                <input type="number" id="quantity" name="quantity"
                                    value="{{ old('quantity', $product->min_quantity) }}" min="{{ $product->min_quantity }}"
                                    max="{{ $product->availableStock() }}"
                                    class="w-full border border-slate-300 focus:border-[#016837] focus:ring-1 focus:ring-[#016837] rounded-xl px-4 py-3 text-sm font-bold text-slate-900 transition outline-none">
                                <p class="text-[11px] text-slate-400 font-medium mt-1">Min : {{ $product->min_quantity }} | Stock : {{ $product->availableStock() }}</p>
                            </div>

                            <div>
                                <label for="destination_city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Ville de destination <span class="text-[#E30613]">*</span>
                                </label>
                                <select id="destination_city" name="destination_city" required
                                    class="w-full border border-slate-300 focus:border-[#016837] focus:ring-1 focus:ring-[#016837] rounded-xl px-4 py-3 text-xs font-semibold text-slate-900 transition outline-none">
                                    <option value="">-- Choisir une ville --</option>
                                    @forelse ($cities as $city)
                                        <option value="{{ $city }}" {{ old('destination_city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Aucune ville desservie pour le moment</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        {{-- Opérateur & Numéro --}}
                        <div class="border-t border-slate-200 pt-5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                                Mode de paiement Mobile <span class="text-[#E30613]">*</span>
                            </label>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <label class="flex items-center gap-3 border border-slate-200 rounded-xl p-3 cursor-pointer transition hover:bg-yellow-50/50 has-[:checked]:border-[#F9A01B] has-[:checked]:bg-yellow-50/80">
                                    <input type="radio" name="payer_operator" value="mtn"
                                        class="w-4 h-4 text-[#F9A01B] focus:ring-[#F9A01B]"
                                        {{ old('payer_operator', 'mtn') === 'mtn' ? 'checked' : '' }} required>
                                    <span class="font-extrabold text-slate-800 text-xs">MTN Mobile Money</span>
                                </label>

                                <label class="flex items-center gap-3 border border-slate-200 rounded-xl p-3 cursor-pointer transition hover:bg-orange-50/50 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/80">
                                    <input type="radio" name="payer_operator" value="orange"
                                        class="w-4 h-4 text-orange-600 focus:ring-orange-500"
                                        {{ old('payer_operator') === 'orange' ? 'checked' : '' }}>
                                    <span class="font-extrabold text-slate-800 text-xs">Orange Money</span>
                                </label>
                            </div>

                            <div class="flex">
                                <span class="inline-flex items-center px-3.5 border border-r-0 border-slate-300 rounded-l-xl bg-slate-100 text-slate-600 text-xs font-bold">+237</span>
                                <input type="tel" name="payer_phone"
                                    value="{{ old('payer_phone', auth()->user()->phone_momo) }}" required
                                    placeholder="655123456"
                                    class="flex-1 border border-slate-300 focus:border-[#016837] focus:ring-1 focus:ring-[#016837] rounded-r-xl px-4 py-2.5 text-sm font-bold text-slate-900 transition outline-none">
                            </div>
                        </div>

                        {{-- Note optionnelle --}}
                        <div>
                            <label for="note" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Note pour le vendeur <span class="text-slate-400 font-normal capitalize">(Optionnel)</span>
                            </label>
                            <input type="text" id="note" name="note" value="{{ old('note') }}"
                                placeholder="Ex: Précision sur la couleur, la livraison..."
                                class="w-full border border-slate-300 focus:border-[#016837] focus:ring-1 focus:ring-[#016837] rounded-xl px-4 py-2.5 text-xs font-medium text-slate-900 transition outline-none">
                        </div>

                    </div>

                    {{-- COLONNE DROITE (5/12) : Récapitulatif Financier --}}
                    <div class="lg:col-span-5 flex flex-col justify-between bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100">

                        <div>
                            <h2 class="font-black text-slate-800 text-sm uppercase tracking-wider mb-4 border-b border-emerald-100 pb-2">Récapitulatif de la commande</h2>

                            <div class="space-y-3 text-xs" id="summary">
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Prix des articles</span>
                                    <span class="font-bold text-slate-800" id="s-subtotal">—</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Frais de protection ({{ \App\Models\PlatformSetting::getValue('protection_rate') }}%)</span>
                                    <span class="font-bold text-slate-800" id="s-protection">—</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Frais Mobile Money ({{ \App\Models\PlatformSetting::getValue('gateway_collect_rate') }}%)</span>
                                    <span class="font-bold text-slate-800" id="s-elgiopay">—</span>
                                </div>
                            </div>
                        </div>

                        {{-- Total final & Bouton de validation --}}
                        <div class="mt-8 pt-4 border-t border-emerald-200/80">
                            <div class="flex justify-between items-baseline mb-6">
                                <span class="text-xs uppercase font-black text-slate-700 tracking-wider">Total à payer</span>
                                <div class="text-2xl sm:text-3xl font-black text-[#016837] tracking-tight" id="s-total">
                                    — FCFA
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#016837] hover:bg-[#01522b] active:bg-[#013d20] text-white font-extrabold py-3.5 px-4 rounded-xl transition-all shadow-md shadow-[#016837]/20 text-sm flex items-center justify-center gap-2 group cursor-pointer">
                                <span>Confirmer la commande</span>
                                <svg class="w-4 h-4 text-[#F9A01B] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>

                    </div>

                </div>
            </form>
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
@endsection