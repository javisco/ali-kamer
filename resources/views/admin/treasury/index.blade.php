@extends('layouts.admin')
@section('title', 'Trésorerie')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 space-y-6">

    <div>
        <h1 class="text-xl font-black text-slate-900">Trésorerie plateforme</h1>
        <p class="text-sm text-slate-500 mt-1">
            Ce que la plateforme peut retirer sans toucher à l'argent des vendeurs ou des agences.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-success-50 border border-success-200 text-primary-600 rounded-xl px-4 py-3 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if($snapshot['balance_error'])
        <div class="bg-danger-50 border border-danger-200 text-danger rounded-xl px-4 py-3 text-sm font-bold">
            ⚠️ Solde Elgiopay indisponible ({{ $snapshot['balance_error'] }}). Retrait bloqué par sécurité.
        </div>
    @endif

    {{-- Cartes de synthèse --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Solde réel Elgiopay</p>
            <p class="text-2xl font-black text-slate-800">
                {{ $snapshot['live_balance'] !== null ? number_format($snapshot['live_balance'], 0, ',', ' ') : '—' }}
                <span class="text-xs font-bold text-slate-400">FCFA</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-warning-200 p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-accent-500 mb-1">Dû aux vendeurs/agences</p>
            <p class="text-2xl font-black text-accent-500">
                {{ number_format($snapshot['total_liabilities'], 0, ',', ' ') }}
                <span class="text-xs font-bold">FCFA</span>
            </p>
            <p class="text-[10px] text-slate-400 mt-1">
                Vendeurs : {{ number_format($snapshot['sellers_liability'], 0, ',', ' ') }} ·
                Agences : {{ number_format($snapshot['agencies_liability'], 0, ',', ' ') }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border-2 border-primary-600/40 p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-primary-600 mb-1">Retirable maintenant</p>
            <p class="text-2xl font-black text-primary-600">
                {{ $snapshot['withdrawable'] !== null ? number_format($snapshot['withdrawable'], 0, ',', ' ') : '—' }}
                <span class="text-xs font-bold">FCFA</span>
            </p>
        </div>
    </div>

    <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-500">
        Estimation indicative reconstruite depuis les commandes payées : 
        <strong class="text-slate-700">{{ number_format($snapshot['expected_profit'], 0, ',', ' ') }} FCFA</strong>
        — sert uniquement à repérer un écart anormal avec le solde réel ci-dessus.
    </div>

    {{-- Formulaire de retrait --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="font-black text-slate-900 mb-4">Effectuer un retrait</h2>

        @if($errors->any())
            <div class="bg-danger-50 border border-danger-200 text-danger rounded-xl px-4 py-3 text-xs font-bold mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.treasury.withdraw') }}" class="space-y-4">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Montant (FCFA)</label>
                    <input type="number" name="amount" min="1000"
                           max="{{ $snapshot['withdrawable'] }}"
                           value="{{ old('amount') }}"
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Numéro MoMo destinataire</label>
                    <input type="text" name="phone" placeholder="677123456"
                           value="{{ old('phone') }}"
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Opérateur</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="operator" value="mtn" checked> MTN
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="operator" value="orange"> Orange
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Note (optionnel)</label>
                <input type="text" name="note" placeholder="Ex: virement compte société"
                       class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm">
            </div>

            <div class="border-t border-slate-200 pt-4">
                <label class="block text-xs font-bold text-danger mb-1">
                    Confirmez avec votre mot de passe
                </label>
                <input type="password" name="password"
                       class="w-full border border-danger-200 rounded-xl px-4 py-2.5 text-sm" required>
            </div>

            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-black py-3 rounded-xl text-sm uppercase tracking-wider">
                Confirmer le retrait
            </button>
        </form>
    </div>

    {{-- Historique --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 font-black text-xs uppercase tracking-wider text-slate-700">
            Historique des retraits
        </div>
        @forelse($history as $tx)
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 text-sm">
                <div>
                    <p class="font-bold text-slate-800">{{ $tx->admin->name }} — {{ $tx->phone }} ({{ strtoupper($tx->operator) }})</p>
                    @if($tx->note)<p class="text-xs text-slate-400">{{ $tx->note }}</p>@endif
                    <p class="text-[10px] text-slate-400">{{ $tx->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <p class="font-black text-danger">- {{ number_format($tx->amount, 0, ',', ' ') }} FCFA</p>
            </div>
        @empty
            <div class="px-5 py-8 text-center text-slate-400 text-xs">Aucun retrait effectué.</div>
        @endforelse
        <div class="px-5 py-3">{{ $history->links() }}</div>
    </div>

</div>
@endsection