@extends('layouts.admin')

@section('title', 'Historique — ' . $agency->name)

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        {{-- En-tête --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('admin.agencies.earnings') }}"
                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 text-slate-500 hover:text-primary-600 hover:border-primary-600/20 flex items-center justify-center shadow-sm transition">
                    ←
                </a>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-primary-600">
                        Réseau Ali-Kamer
                    </p>

                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        {{ $agency->name }}
                    </h1>
                </div>
            </div>

            <div class="h-1 rounded-full bg-gradient-to-r from-primary-600 via-[#FCD116] to-[#CE1126]"></div>
        </div>

        {{-- Stats financières --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Gains totaux
                </p>
                <p class="text-lg sm:text-xl font-black text-slate-900 mt-2">
                    {{ number_format($stats['total_earned'], 0, ',', ' ') }}
                    <span class="text-[10px] text-slate-400">FCFA</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-primary-600/15 shadow-sm p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-primary-600">
                    Disponible
                </p>
                <p class="text-lg sm:text-xl font-black text-primary-600 mt-2">
                    {{ number_format($stats['wallet_available'], 0, ',', ' ') }}
                    <span class="text-[10px] text-primary-600/60">FCFA</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-[#FCD116]/40 shadow-sm p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-warning-600">
                    En attente
                </p>
                <p class="text-lg sm:text-xl font-black text-warning-600 mt-2">
                    {{ number_format($agency->wallet_pending, 0, ',', ' ') }}
                    <span class="text-[10px] text-warning">FCFA</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Total retiré
                </p>
                <p class="text-lg sm:text-xl font-black text-slate-900 mt-2">
                    {{ number_format($stats['total_withdrawn'], 0, ',', ' ') }}
                    <span class="text-[10px] text-slate-400">FCFA</span>
                </p>
            </div>

        </div>

        {{-- Suivi des colis --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">

            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-xl bg-primary-600/10 text-primary-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M5 8h14M5 12h14M5 16h9"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-sm font-black text-slate-900">
                        Suivi des colis
                    </h2>
                    <p class="text-[11px] text-slate-400">
                        Activité logistique de l’agence
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">
                        {{ $deliveryStats['deposited'] }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Colis déposés
                    </p>
                </div>

                <div class="text-center p-4 bg-primary-600/5 rounded-xl">
                    <p class="text-2xl sm:text-3xl font-black text-primary-600">
                        {{ $deliveryStats['delivered'] }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Colis livrés
                    </p>
                </div>

                <div class="text-center p-4 bg-warning-50 rounded-xl">
                    <p class="text-2xl sm:text-3xl font-black text-warning-600">
                        {{ $deliveryStats['in_transit'] }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-1">
                        En transit / non livrés
                    </p>
                </div>

                <div class="text-center p-4 bg-[#CE1126]/5 rounded-xl">
                    <p class="text-2xl sm:text-3xl font-black text-[#CE1126]">
                        {{ $deliveryStats['rate'] }}%
                    </p>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Taux de livraison
                    </p>
                </div>

            </div>

            @if($deliveryStats['in_transit'] > 5)
                <div class="mt-4 bg-warning-50 border border-warning-200 rounded-xl p-3 text-xs font-medium text-warning-800">
                    <span class="font-black">⚠ Attention :</span>
                    {{ $deliveryStats['in_transit'] }} colis déposés chez cette agence
                    n'ont pas encore été livrés.
                </div>
            @endif
        </div>

        {{-- Historique transactions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">
                    Transactions
                </h2>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Historique financier de l’agence
                </p>
            </div>

            @if($history->isEmpty())

                <div class="px-5 py-12 text-center">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="M12 8v8m-4-4h8"/>
                        </svg>
                    </div>

                    <p class="text-sm font-bold text-slate-700">
                        Aucune transaction
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        L’historique financier apparaîtra ici.
                    </p>
                </div>

            @else

                <div class="divide-y divide-slate-100">

                    @foreach($history as $tx)

                        <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50/70 transition">

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">
                                    {{ $tx->typeLabel() }}
                                </p>

                                @if($tx->order)
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Commande {{ $tx->order->reference }}
                                    </p>
                                @endif

                                <p class="text-[10px] text-slate-300 mt-0.5">
                                    {{ $tx->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <p class="font-black text-sm
                                    {{ $tx->isCredit() ? 'text-primary-600' : 'text-[#CE1126]' }}">
                                    {{ $tx->isCredit() ? '+' : '-' }}
                                    {{ number_format($tx->amount, 0, ',', ' ') }}
                                    <span class="text-[9px]">FCFA</span>
                                </p>

                                <p class="text-[10px] text-slate-300 mt-0.5">
                                    Solde :
                                    {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $history->links() }}
                </div>

            @endif
        </div>

    </div>
</div>
@endsection