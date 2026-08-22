@extends('base')
@section('title', 'Historique — ' . $agency->name)
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.agencies.earnings') }}"
           class="text-gray-400 hover:text-gray-600">←</a>
        <h1 class="text-2xl font-extrabold text-gray-900">
            {{ $agency->name }} — Historique & Stats
        </h1>
    </div>

    {{-- Stats financières --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Gains totaux</p>
            <p class="text-xl font-extrabold text-gray-700">
                {{ number_format($stats['total_earned'], 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-4">
            <p class="text-xs text-indigo-500 mb-1">Disponible</p>
            <p class="text-xl font-extrabold text-indigo-600">
                {{ number_format($stats['wallet_available'], 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-orange-100 shadow-sm p-4">
            <p class="text-xs text-orange-500 mb-1">En attente</p>
            <p class="text-xl font-extrabold text-orange-500">
                {{ number_format($agency->wallet_pending, 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Total retiré</p>
            <p class="text-xl font-extrabold text-gray-700">
                {{ number_format($stats['total_withdrawn'], 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    {{-- Stats dépôt / livraison --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <h2 class="font-bold text-gray-800 mb-4">Suivi des colis</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-gray-50 rounded-xl">
                <p class="text-3xl font-extrabold text-gray-900">
                    {{ $deliveryStats['deposited'] }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Colis déposés</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-xl">
                <p class="text-3xl font-extrabold text-emerald-600">
                    {{ $deliveryStats['delivered'] }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Colis livrés</p>
            </div>
            <div class="text-center p-3 bg-orange-50 rounded-xl">
                <p class="text-3xl font-extrabold text-orange-500">
                    {{ $deliveryStats['in_transit'] }}
                </p>
                <p class="text-xs text-gray-500 mt-1">En transit / non livrés</p>
            </div>
            <div class="text-center p-3 bg-indigo-50 rounded-xl">
                <p class="text-3xl font-extrabold text-indigo-600">
                    {{ $deliveryStats['rate'] }}%
                </p>
                <p class="text-xs text-gray-500 mt-1">Taux de livraison</p>
            </div>
        </div>
        @if($deliveryStats['in_transit'] > 5)
            <div class="mt-4 bg-orange-50 border border-orange-200 rounded-xl p-3 text-sm text-orange-700">
                ⚠ {{ $deliveryStats['in_transit'] }} colis déposés chez cette agence
                n'ont pas encore été livrés. À surveiller.
            </div>
        @endif
    </div>

    {{-- Historique transactions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50">
            <h2 class="font-bold text-gray-800">Transactions</h2>
        </div>
        @if($history->isEmpty())
            <p class="px-5 py-8 text-center text-gray-400 text-sm">
                Aucune transaction.
            </p>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($history as $tx)
                    <div class="flex items-center justify-between px-5 py-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tx->typeLabel() }}
                            </p>
                            @if($tx->order)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Commande {{ $tx->order->reference }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-300">
                                {{ $tx->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-bold text-sm
                               {{ $tx->isCredit() ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $tx->isCredit() ? '+' : '-' }}
                                {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                            </p>
                            <p class="text-xs text-gray-300">
                                Solde : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-4 border-t">{{ $history->links() }}</div>
        @endif
    </div>

</div>
</div>
@endsection