@extends('layouts.seller')

@section('title', 'Mon portefeuille - Ali-Kamer')

@section('content')
    <div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
        <div class="max-w-3xl mx-auto space-y-5">

            <!-- 1. HERO BANNER ALI-KAMER -->
            <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                            Finances & Retraits
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Mon Portefeuille
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm mt-0.5 font-medium">
                            Suivez vos soldes, vos transactions et effectuez vos retraits vers Mobile Money.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 2. ALERTE DE SUCCÈS -->
            @if (session('success'))
                <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 3. SOLDES (TOTAL, DISPONIBLE, EN ATTENTE) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">

                {{-- Solde Total --}}
                <div class="bg-white rounded-2xl border-2 border-[#016837] p-5 shadow-xs relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-[#016837]"></div>
                    <p class="text-[11px] font-black uppercase tracking-wider text-[#016837] mb-1 flex items-center justify-between">
                        <span>Solde Total</span>
                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-50 text-[#016837] font-bold">Actifs</span>
                    </p>
                    <p class="text-2xl sm:text-3xl font-black text-[#0a1b12]">
                        {{ number_format($user->wallet_available + $user->wallet_pending, 0, ',', ' ') }}
                        <span class="text-xs font-bold text-gray-400">FCFA</span>
                    </p>
                    <p class="text-[11px] text-gray-500 font-medium mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-[#016837] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Total de vos avoirs
                    </p>
                </div>

                {{-- Solde disponible --}}
                <div class="bg-white rounded-2xl border border-emerald-200 p-5 shadow-xs relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-[#00843D]"></div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#00843D] mb-1">
                        Solde Disponible
                    </p>
                    <p class="text-2xl sm:text-3xl font-black text-[#00843D]">
                        {{ number_format($user->wallet_available, 0, ',', ' ') }}
                        <span class="text-xs font-bold text-[#00843D]/70">FCFA</span>
                    </p>
                    <p class="text-xs text-[#00843D]/80 font-medium mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0 text-[#00843D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Retirable vers Mobile Money
                    </p>
                </div>

                {{-- Solde en attente (Séquestre) --}}
                <div class="bg-white rounded-2xl border border-amber-200 p-5 shadow-xs relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-[#FCD116]"></div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700 mb-1">
                        En Attente (Séquestre)
                    </p>
                    <p class="text-2xl sm:text-3xl font-black text-amber-700">
                        {{ number_format($user->wallet_pending, 0, ',', ' ') }}
                        <span class="text-xs font-bold text-amber-600/70">FCFA</span>
                    </p>
                    <p class="text-xs text-amber-700/80 font-medium mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Libérés après livraison
                    </p>
                </div>

            </div>

            <!-- 4. BOUTON D'ACTION (RETRAIT) -->
            @if ($user->wallet_available >= 1000)
                <a href="{{ route('seller.wallet.withdraw') }}"
                    class="block w-full bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] font-black
                    py-3.5 rounded-xl text-center shadow-xs transition text-sm">
                    Demander un retrait Mobile Money
                </a>
            @else
                <div class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 rounded-xl text-center text-sm cursor-not-allowed border border-gray-300/60">
                    Solde insuffisant pour un retrait (min. 1 000 FCFA)
                </div>
            @endif

            <!-- 5. HISTORIQUE DES TRANSACTIONS -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="px-5 py-3.5 bg-[#F7F7F2]/80 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-black text-xs uppercase tracking-wider text-[#0a1b12]">
                        Historique des transactions
                    </h2>
                </div>

                @if ($transactions->isEmpty())
                    <div class="px-5 py-12 text-center text-gray-400 text-xs">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Aucune transaction enregistrée pour le moment.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($transactions as $tx)
                            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-[#F7F7F2]/40 transition">
                                <div class="flex-1 min-w-0 pr-4">
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs sm:text-sm font-bold text-[#0a1b12]">
                                            {{ $tx->typeLabel() }}
                                        </p>
                                        @if ($tx->isEscrowTransfer())
                                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                ⇄ Déblocage
                                            </span>
                                        @elseif ($tx->isEscrow())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                Séquestre
                                            </span>
                                        @endif
                                    </div>
                                    @if ($tx->note)
                                        <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $tx->note }}</p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">
                                        {{ $tx->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>

                                {{-- Montant & Solde après --}}
                                <div class="text-right shrink-0">
                                    @if ($tx->isEscrowTransfer())
                                        <p class="font-black text-xs sm:text-sm text-blue-700">
                                            ⇄ {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                        </p>
                                    @elseif ($tx->isCredit())
                                        <p class="font-black text-xs sm:text-sm text-[#016837]">
                                            + {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                        </p>
                                    @else
                                        <p class="font-black text-xs sm:text-sm text-[#E30613]">
                                            - {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                        </p>
                                    @endif

                                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">
                                        {{ $tx->balanceLabel() }} : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($transactions->hasPages())
                        <div class="px-5 py-3 border-t border-gray-100">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
@endsection