@extends('layouts.seller')

@section('title', 'Mon portefeuille')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mon portefeuille</h1>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Soldes --}}
            <div class="grid grid-cols-2 gap-4 mb-6">

                {{-- Solde en attente --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">En attente</p>
                    <p class="text-2xl font-extrabold text-gray-400">
                        {{ number_format($user->wallet_pending, 0, ',', ' ') }}
                        <span class="text-sm font-semibold">FCFA</span>
                    </p>
                    {{-- Fonds séquestrés — non retirables --}}
                    <p class="text-xs text-gray-400 mt-1">
                        Fonds séquestrés — libérés après livraison confirmée
                    </p>
                </div>

                {{-- Solde disponible --}}
                <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5">
                    <p class="text-xs text-indigo-500 uppercase tracking-wider mb-1">Disponible</p>
                    <p class="text-2xl font-extrabold text-indigo-600">
                        {{ number_format($user->wallet_available, 0, ',', ' ') }}
                        <span class="text-sm font-semibold">FCFA</span>
                    </p>
                    {{-- Fonds retirables vers MoMo --}}
                    <p class="text-xs text-indigo-400 mt-1">
                        Retirable vers MoMo (min. 1 000 FCFA)
                    </p>
                </div>
            </div>

            {{-- Bouton retrait --}}
            @if ($user->wallet_available >= 1000)
                <a href="{{ route('seller.wallet.withdraw') }}"
                    class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                  py-3.5 rounded-2xl text-center mb-6 transition text-sm">
                    Retirer vers Mobile Money
                </a>
            @else
                {{-- Bouton désactivé si solde insuffisant --}}
                <div
                    class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 rounded-2xl
                    text-center mb-6 text-sm cursor-not-allowed">
                    Solde insuffisant pour un retrait (min. 1 000 FCFA)
                </div>
            @endif

            {{-- Historique des transactions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Historique des transactions</h2>
                </div>

                @if ($transactions->isEmpty())
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        Aucune transaction pour l'instant.
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach ($transactions as $tx)
                            <div class="flex items-center justify-between px-5 py-4">
                                <div class="flex-1 min-w-0">
                                    {{-- Libellé de la transaction --}}
                                    <p class="text-sm font-medium text-gray-900">{{ $tx->typeLabel() }}</p>
                                    @if ($tx->note)
                                        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $tx->note }}</p>
                                    @endif
                                    <p class="text-xs text-gray-300 mt-0.5">
                                        {{ $tx->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>

                                {{-- Montant avec couleur selon crédit/débit --}}
                                <div class="text-right flex-shrink-0 ml-4">
                                    <p
                                        class="font-bold text-sm {{ $tx->isCredit() ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $tx->isCredit() ? '+' : '-' }}
                                        {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                    </p>
                                    {{-- Solde après opération pour audit --}}
                                    <p class="text-xs text-gray-300 mt-0.5">
                                        Solde : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-5 py-4 border-t border-gray-50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
