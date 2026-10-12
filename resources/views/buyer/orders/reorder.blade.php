@extends('layouts.buyer')

@section('title', 'Commander à nouveau — ' . $order->reference)

@section('content')
<div class="min-h-screen bg-[#F7F7F2] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        {{-- En-tête --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('buyer.orders.show', $order) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-[#016837] mb-2 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Retour aux détails de la commande</span>
                </a>
                <div class="flex items-center gap-2.5">
                    <div class="w-3 h-3 rounded-full bg-[#016837]"></div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Commander à nouveau
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    À partir de la commande <span class="font-bold text-slate-700">{{ $order->reference }}</span> (passée le {{ $order->created_at->format('d/m/Y') }})
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-xl shadow-2xs">
                    {{ $prepared['available_count'] }} disponible(s) / {{ count($prepared['items']) }} article(s)
                </span>
            </div>
        </div>

        {{-- Messages d'alerte / feedback --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-[#E30613] px-4 py-3 rounded-2xl mb-6 text-xs font-bold flex items-center gap-2 shadow-xs">
                <span>⚠️ {{ session('error') }}</span>
            </div>
        @endif

        @if ($prepared['has_price_changes'])
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3.5 rounded-2xl mb-6 text-xs font-medium flex items-start gap-2.5 shadow-xs">
                <span class="text-base">💡</span>
                <div>
                    <span class="font-bold">Actualisation des tarifs :</span> Certains articles ont vu leur prix évoluer depuis votre dernière commande. Les nouveaux tarifs en vigueur sont affichés ci-dessous.
                </div>
            </div>
        @endif

        @if ($prepared['unavailable_count'] > 0)
            <div class="bg-slate-100 border border-slate-200 text-slate-700 px-4 py-3 rounded-2xl mb-6 text-xs font-medium flex items-center gap-2">
                <span>ℹ️</span>
                <span>{{ $prepared['unavailable_count'] }} article(s) de cette commande ne sont plus disponibles (rupture de stock ou boutique inactive) et ont été désactivés.</span>
            </div>
        @endif

        {{-- Formulaire de rachat --}}
        <form method="POST" action="{{ route('buyer.orders.reorder', $order) }}" id="reorderForm">
            @csrf

            <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden mb-6">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs font-extrabold text-slate-800">
                        <input type="checkbox" id="masterCheckbox" checked
                               class="w-4 h-4 rounded text-[#016837] focus:ring-[#016837] border-slate-300">
                        <span>Sélectionner tous les articles disponibles</span>
                    </label>
                    <span class="text-[11px] font-bold text-slate-400">
                        Vérifié en temps réel
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($prepared['items'] as $item)
                        @php
                            $statusBadge = match($item['stock_status']) {
                                'available'    => ['class' => 'bg-emerald-50 text-[#016837] border-emerald-200', 'label' => 'En stock ✓'],
                                'partial_stock'=> ['class' => 'bg-amber-50 text-amber-800 border-amber-200', 'label' => $item['status_message']],
                                'out_of_stock' => ['class' => 'bg-red-50 text-[#E30613] border-red-200', 'label' => 'Épuisé'],
                                default        => ['class' => 'bg-slate-100 text-slate-500 border-slate-200', 'label' => 'Indisponible'],
                            };
                        @endphp
                        <div class="p-5 flex items-start gap-4 transition-colors {{ $item['can_reorder'] ? 'hover:bg-slate-50/60' : 'bg-slate-50/40 opacity-75' }}">
                            {{-- Checkbox --}}
                            <div class="pt-2">
                                <input type="checkbox" name="selected_items[]" value="{{ $item['order_item_id'] }}"
                                       class="item-checkbox w-4 h-4 rounded text-[#016837] focus:ring-[#016837] border-slate-300"
                                       {{ $item['can_reorder'] ? 'checked' : 'disabled' }}>
                            </div>

                            {{-- Visuel Produit --}}
                            <div class="shrink-0">
                                @if ($item['product_image'])
                                    <img src="{{ asset('storage/' . $item['product_image']) }}"
                                         alt="{{ $item['product_title'] }}"
                                         class="w-16 h-16 rounded-2xl object-cover bg-slate-100 border border-slate-200 shadow-2xs">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Informations produit --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                    <div>
                                        <p class="font-extrabold text-slate-900 text-sm">
                                            {{ $item['product_title'] }}
                                        </p>
                                        @if ($item['variant_label'])
                                            <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                                Variante : {{ $item['variant_label'] }}
                                            </p>
                                        @endif
                                        <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                            <span>Boutique :</span>
                                            <span class="font-bold text-slate-600">{{ $item['shop_name'] }}</span>
                                        </p>
                                    </div>

                                    {{-- Badge de statut du stock --}}
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black border uppercase tracking-wider self-start {{ $statusBadge['class'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </div>

                                {{-- Tarification & Quantité --}}
                                <div class="mt-2.5 flex flex-wrap items-center justify-between gap-3 pt-2.5 border-t border-slate-100 text-xs">
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-500 font-medium">
                                            Qté demandée : <strong class="text-slate-800">{{ $item['requested_qty'] }}</strong>
                                        </span>
                                        @if ($item['available_qty'] < $item['requested_qty'] && $item['available_qty'] > 0)
                                            <span class="text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                                                Max dispo : {{ $item['available_qty'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        @if ($item['price_difference'] !== 0)
                                            <div class="flex items-center gap-2">
                                                <span class="line-through text-slate-400 text-xs font-medium">
                                                    {{ number_format($item['old_unit_price'], 0, ',', ' ') }} FCFA
                                                </span>
                                                <span class="font-black text-[#016837] text-sm">
                                                    {{ number_format($item['current_unit_price'], 0, ',', ' ') }} FCFA
                                                </span>
                                                <span class="text-[10px] font-black px-1.5 py-0.5 rounded {{ $item['price_difference'] > 0 ? 'bg-red-50 text-[#E30613]' : 'bg-emerald-50 text-[#016837]' }}">
                                                    {{ $item['price_difference'] > 0 ? '+' : '' }}{{ number_format($item['price_difference'], 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                        @else
                                            <span class="font-black text-slate-900 text-sm">
                                                {{ number_format($item['current_unit_price'], 0, ',', ' ') }} FCFA
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions de soumission --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="font-extrabold text-slate-900 text-sm">
                        Prêt à passer la commande ?
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Les articles sélectionnés seront ajoutés à votre panier persistant pour validation.
                    </p>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('buyer.orders.show', $order) }}"
                       class="flex-1 sm:flex-initial text-center px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-extrabold text-xs hover:bg-slate-50 transition">
                        Annuler
                    </a>

                    @if ($prepared['can_reorder_any'])
                        <button type="submit"
                                class="flex-1 sm:flex-initial bg-[#016837] hover:bg-[#01522b] text-white font-extrabold text-xs px-6 py-3 rounded-xl transition shadow-md shadow-[#016837]/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-[#F9A01B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Ajouter au panier & commander</span>
                        </button>
                    @else
                        <button type="button" disabled
                                class="flex-1 sm:flex-initial bg-slate-200 text-slate-400 font-extrabold text-xs px-6 py-3 rounded-xl cursor-not-allowed">
                            Aucun article disponible
                        </button>
                    @endif
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const master = document.getElementById('masterCheckbox');
        const checkboxes = document.querySelectorAll('.item-checkbox:not(:disabled)');

        if (master) {
            master.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = master.checked);
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!cb.checked && master) {
                    master.checked = false;
                } else if (Array.from(checkboxes).every(c => c.checked) && master) {
                    master.checked = true;
                }
            });
        });
    });
</script>
@endsection
