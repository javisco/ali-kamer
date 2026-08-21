@extends('base')
@section('title', 'Wallet — ' . auth()->user()->managedAgency->name)
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agency.dashboard') }}"
           class="text-gray-400 hover:text-gray-600">←</a>
        <h1 class="text-2xl font-extrabold text-gray-900">Wallet agence</h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800
                    px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700
                    px-4 py-3 rounded-xl mb-6 text-sm">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    {{-- Soldes --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 mb-1">Gains totaux</p>
            <p class="text-2xl font-extrabold text-gray-700">
                {{ number_format($stats['total_earned'], 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5">
            <p class="text-xs text-indigo-500 mb-1">Disponible</p>
            <p class="text-2xl font-extrabold text-indigo-600">
                {{ number_format($stats['wallet_available'], 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    {{-- Retrait --}}
    @if($agency->phone_momo)
        @if($stats['wallet_available'] >= 1000)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-4">Retrait</h2>
                <form method="POST" action="{{ route('agency.withdraw') }}"
                      class="flex gap-3">
                    @csrf
                    <div class="flex-1">
                        <input type="number" name="amount"
                               min="1000"
                               max="{{ $stats['wallet_available'] }}"
                               placeholder="Montant (min 1 000 FCFA)"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                                      focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">
                            Vers {{ strtoupper($agency->momo_operator) }} — {{ $agency->phone_momo }}
                        </p>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                                   px-6 rounded-xl transition text-sm">
                        Retirer
                    </button>
                </form>
            </div>
        @else
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 mb-6 text-sm text-gray-500">
                Solde insuffisant pour un retrait (minimum 1 000 FCFA).
            </div>
        @endif
    @else
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6 text-sm text-orange-700">
            ⚠ Aucun numéro MoMo configuré.
            Contactez l'administrateur pour configurer votre numéro de retrait.
        </div>
    @endif

    {{-- Historique --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50">
            <h2 class="font-bold text-gray-800">Historique des transactions</h2>
        </div>

        @if($history->isEmpty())
            <p class="px-5 py-8 text-center text-gray-400 text-sm">
                Aucune transaction pour l'instant.
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
                            @if($tx->note)
                                <p class="text-xs text-gray-300 mt-0.5">{{ $tx->note }}</p>
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
            <div class="px-5 py-4 border-t border-gray-50">
                {{ $history->links() }}
            </div>
        @endif
    </div>

</div>
</div>
@endsection