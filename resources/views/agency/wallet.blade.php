@extends('base')
@section('title', 'Wallet — ' . auth()->user()->managedAgency->name)

@section('content')
<div class="bg-slate-50/60 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Navigation & En-tête --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('agency.dashboard') }}"
               class="h-10 w-10 rounded-xl bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 flex items-center justify-center transition shadow-xs">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Wallet Agence</h1>
                <p class="text-xs font-medium text-slate-500">Gestion des gains et des retraits Mobile Money</p>
            </div>
        </div>

        {{-- Notifications Flash --}}
        @if(session('success'))
            <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3.5 rounded-xl mb-6 text-sm flex items-center gap-3 shadow-xs">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-white text-xs font-bold">✓</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-danger-50 border border-danger-200 text-[#CE1126] px-4 py-3.5 rounded-xl mb-6 text-sm shadow-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Veuillez corriger les erreurs suivantes :
                </div>
                <ul class="list-disc pl-5 space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Encart des Soldes — 3 colonnes : Gains totaux / En attente / Retirable --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gains Totaux</span>
                    <span class="p-2.5 rounded-xl bg-slate-100 text-slate-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-2xl sm:text-3xl font-black text-slate-900">
                        {{ number_format($stats['total_earned'], 0, ',', ' ') }} <span class="text-sm font-bold text-slate-500">FCFA</span>
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Commissions libérées, cumul historique</p>
                </div>
            </div>

            {{-- En attente — même code couleur ambre que côté vendeur --}}
            <div class="bg-white rounded-2xl border-2 border-accent-500/30 shadow-xs p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-accent-500 uppercase tracking-wider">En attente</span>
                    <span class="p-2.5 rounded-xl bg-warning-50 text-accent-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-2xl sm:text-3xl font-black text-accent-500">
                        {{ number_format($stats['wallet_pending'], 0, ',', ' ') }} <span class="text-sm font-bold text-slate-500">FCFA</span>
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Colis déposés, pas encore livrés</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-primary-600/30 shadow-xs p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary-600/5 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-primary-600 uppercase tracking-wider">Solde Retirable</span>
                    <span class="p-2.5 rounded-xl bg-success-50 text-primary-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-2xl sm:text-3xl font-black text-primary-600">
                        {{ number_format($stats['wallet_available'], 0, ',', ' ') }} <span class="text-sm font-bold text-slate-500">FCFA</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium px-1 mb-6">
            <svg class="w-3.5 h-3.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
            Le montant « en attente » devient automatiquement retirable dès que le colis est livré.
        </div>

        {{-- Formulaire de Retrait --}}
        @if($agency->phone_momo)
            @if($stats['wallet_available'] >= 1000)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-warning-50 text-warning-600 text-xs font-bold">📲</span>
                        <h2 class="font-extrabold text-slate-900 text-base">Demander un retrait</h2>
                    </div>

                    <form method="POST" action="{{ route('agency.withdraw') }}" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                        @csrf
                        <div class="flex-1 w-full">
                            <input type="number" name="amount"
                                   min="1000"
                                   max="{{ $stats['wallet_available'] }}"
                                   placeholder="Montant (ex: 5 000 FCFA)"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition">
                            <p class="text-xs font-medium text-slate-500 mt-1.5 flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-primary-600"></span>
                                Vers {{ strtoupper($agency->momo_operator) }} : <span class="font-bold text-slate-700">{{ $agency->phone_momo }}</span>
                            </p>
                        </div>
                        <button class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm shadow-sm shadow-success-800/15 shrink-0 self-stretch sm:self-auto">
                            Confirmer le retrait
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 mb-6 text-xs font-semibold text-slate-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Solde insuffisant pour effectuer un retrait (Minimum requis : <strong>1 000 FCFA</strong>).</span>
                </div>
            @endif
        @else
            <div class="bg-warning-50 border border-warning-200 rounded-2xl p-4 mb-6 text-xs font-semibold text-warning-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-warning-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Aucun numéro Mobile Money (MoMo/Orange) configuré. Veuillez contacter l'administration pour configurer vos coordonnées de paiement.</span>
            </div>
        @endif

        {{-- Historique des Transactions --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-extrabold text-slate-900 text-base">Historique des transactions</h2>
                <span class="text-xs font-bold text-slate-400">{{ $history->total() ?? count($history) }} mouvement(s)</span>
            </div>

            @if($history->isEmpty())
                <div class="p-12 text-center">
                    <div class="h-12 w-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 font-bold text-lg">
                        💸
                    </div>
                    <p class="text-slate-800 font-bold text-base">Aucune transaction enregistrée</p>
                    <p class="text-slate-400 text-xs mt-1">Vos futurs crédits et retraits apparaîtront dans cette liste.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($history as $tx)
                        @php($meta = $tx->displayMeta())
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/50 transition">
                            <div class="flex items-center gap-3.5 min-w-0 pr-4">
                                <div class="h-10 w-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 {{ $meta['bg'] }} {{ $meta['text'] }}">
                                    {{ $meta['sign'] === '→' ? '→' : ($meta['sign'] === '+' ? '↙' : '↗') }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-extrabold text-slate-900 truncate">
                                        {{ $tx->typeLabel() }}
                                    </p>
                                    @if($tx->order)
                                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">
                                            Commande <span class="text-slate-700">#{{ $tx->order->reference }}</span>
                                        </p>
                                    @endif
                                    @if($tx->note)
                                        <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $tx->note }}</p>
                                    @endif
                                    <p class="text-[10px] font-medium text-slate-400 mt-0.5">
                                        {{ $tx->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="font-extrabold text-sm {{ $meta['text'] }}">
                                    {{ $meta['sign'] }} {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                </p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                    Solde : <span class="font-semibold text-slate-600">{{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(method_exists($history, 'links'))
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $history->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection