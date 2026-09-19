@extends('layouts.admin')

@section('title', 'Historique — ' . $user->name . ' - Ali-Kamer')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 px-3 sm:px-6">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-primary-600 text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-xs flex items-center justify-center border border-white/20 shrink-0">
                        <svg class="w-6 h-6 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-white mb-1 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                            Gestion Porte-Monnaie
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            {{ $user->name }}
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm font-medium">
                            Historique complet des mouvements de compte et soldes.
                        </p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. SOLDES ACTUELS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- En attente --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Solde en attente</p>
                    <p class="text-xl sm:text-2xl font-black text-slate-500 mt-0.5">
                        {{ number_format($user->wallet_pending, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Disponible --}}
            <div class="bg-white rounded-2xl border border-primary-600/20 shadow-xs p-4 sm:p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-primary-600 uppercase tracking-wider">Solde disponible</p>
                    <p class="text-xl sm:text-2xl font-black text-primary-600 mt-0.5">
                        {{ number_format($user->wallet_available, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-primary-600/10 text-primary-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. LISTE DES TRANSACTIONS -->
        @if ($transactions->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-10 text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aucune transaction enregistrée</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                        Détails des opérations
                    </h2>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($transactions as $tx)
                        <div class="flex items-center justify-between px-5 py-4 hover:bg-primary-600/5 transition gap-4">
                            
                            {{-- Informations transaction --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $tx->isCredit() ? 'bg-primary-600' : 'bg-danger' }}"></span>
                                    <p class="text-xs font-bold text-slate-900 truncate">
                                        {{ $tx->typeLabel() }}
                                    </p>
                                </div>

                                @if ($tx->note)
                                    <p class="text-[11px] text-slate-500 font-medium mt-1 pl-4">
                                        {{ $tx->note }}
                                    </p>
                                @endif

                                <p class="text-[10px] text-slate-400 font-semibold mt-1 pl-4">
                                    {{ $tx->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>

                            {{-- Montant & Solde après --}}
                            <div class="text-right flex-shrink-0">
                                <p class="font-black text-xs sm:text-sm {{ $tx->isCredit() ? 'text-primary-600' : 'text-danger' }}">
                                    {{ $tx->isCredit() ? '+' : '-' }} {{ number_format($tx->amount, 0, ',', ' ') }} <span class="text-[10px] font-bold">FCFA</span>
                                </p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                    Solde : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. PAGINATION -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif

    </div>
</div>
@endsection