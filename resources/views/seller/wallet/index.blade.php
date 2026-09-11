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

            <!-- 3. SOLDES (EN ATTENTE / DISPONIBLE) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

                {{-- Solde en attente — ambre : argent réel, verrouillé, pas "mort" --}}
                <div class="bg-white rounded-2xl border-2 border-[#F9A01B]/30 p-5 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#F9A01B] mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        En Attente (Séquestre)
                    </p>
                    <p class="text-2xl sm:text-3xl font-black text-[#F9A01B]">
                        {{ number_format($user->wallet_pending, 0, ',', ' ') }}
                        <span class="text-xs font-bold text-[#F9A01B]">FCFA</span>
                    </p>
                    <p class="text-xs text-[#F9A01B]/80 font-medium mt-1.5">
                        Argent réel, verrouillé jusqu'à confirmation de livraison
                    </p>
                </div>

                {{-- Solde disponible --}}
                <div class="bg-white rounded-2xl border-2 border-[#016837]/30 p-5 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#016837] mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Solde Disponible
                    </p>
                    <p class="text-2xl sm:text-3xl font-black text-[#016837]">
                        {{ number_format($user->wallet_available, 0, ',', ' ') }}
                        <span class="text-xs font-bold text-[#016837]">FCFA</span>
                    </p>
                    <p class="text-xs text-[#016837]/80 font-medium mt-1.5">
                        Retirable vers Mobile Money (min. 1 000 FCFA)
                    </p>
                </div>

            </div>

            <!-- Repère visuel entre les deux soldes -->
            <div class="flex items-center gap-2 text-[11px] text-gray-400 font-medium px-1">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                Le séquestre devient automatiquement disponible dès que la livraison est confirmée.
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
                            @php($meta = $tx->displayMeta())
                            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-[#F7F7F2]/40 transition">
                                <div class="flex-1 min-w-0 pr-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 text-[10px] font-black {{ $meta['bg'] }} {{ $meta['text'] }}">
                                            {{ $meta['sign'] === '→' ? '→' : ($meta['sign'] === '+' ? '↑' : '↓') }}
                                        </span>
                                        <p class="text-xs sm:text-sm font-bold text-[#0a1b12]">
                                            {{ $tx->typeLabel() }}
                                        </p>
                                    </div>
                                    @if ($tx->note)
                                        <p class="text-[11px] text-gray-400 mt-0.5 ml-8 truncate">{{ $tx->note }}</p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 mt-0.5 ml-8 font-medium">
                                        {{ $tx->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>

                                {{-- Montant --}}
                                <div class="text-right shrink-0">
                                    <p class="font-black text-xs sm:text-sm {{ $meta['text'] }}">
                                        {{ $meta['sign'] }}
                                        {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">
                                        Solde : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
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