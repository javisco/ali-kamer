@extends('layouts.seller')

@section('title', 'Retrait Mobile Money - Ali-Kamer')

@section('content')
    <div class="min-h-screen bg-slate-50 py-6 px-3 sm:px-6">
        <div class="max-w-md mx-auto space-y-5">

            <!-- 1. EN-TÊTE BANNIÈRE ALI-KAMER -->
            <div class="bg-primary-600 text-white rounded-2xl p-5 shadow-xs relative overflow-hidden text-center">
                <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/5 rounded-full pointer-events-none"></div>

                <span class="inline-block bg-accent-500 text-slate-900 text-[10px] font-black uppercase tracking-widest px-3 py-0.5 rounded-full mb-2">
                    Demande de Retrait
                </span>
                <h1 class="text-xl font-black uppercase tracking-wider text-white">
                    Retrait Mobile Money
                </h1>
                <p class="text-white/80 text-xs font-medium mt-1">
                    Traitement garanti sous 24h ouvrables.
                </p>
            </div>

            <!-- 2. GESTION DES ERreurs -->
            @if ($errors->any())
                <div class="bg-danger/10 border border-danger/20 text-danger rounded-xl p-4 text-xs font-bold space-y-1 shadow-xs">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $error }}</span>
                        </p>
                    @endforeach
                </div>
            @endif

            <!-- 3. SOLDE DISPONIBLE -->
            <div class="bg-white border border-primary-600/30 rounded-2xl p-4 text-center shadow-xs">
                <p class="text-[11px] font-bold uppercase tracking-wider text-primary-600 mb-1">
                    Solde Disponible au Retrait
                </p>
                <p class="text-3xl font-black text-primary-600">
                    {{ number_format($user->wallet_available, 0, ',', ' ') }}
                    <span class="text-xs font-bold text-primary-600">FCFA</span>
                </p>
            </div>

            <!-- 4. FORMULAIRE DE RETRAIT -->
            <form method="POST" action="{{ route('seller.wallet.withdraw.post') }}" class="space-y-4">
                @csrf

                {{-- Numéro MoMo --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
                    <h2 class="font-black text-xs uppercase tracking-wider text-slate-900 mb-3 border-b border-slate-100 pb-2">
                        Compte de réception MoMo
                    </h2>
                    <div class="space-y-2 text-xs font-medium">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Opérateur</span>
                            <span class="font-black text-slate-900 uppercase bg-slate-50 px-2.5 py-0.5 rounded-md border border-slate-200">
                                {{ $user->momo_operator }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Numéro de téléphone</span>
                            <span class="font-bold text-slate-900">+237 {{ $user->phone_momo }}</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pour modifier ce numéro enregistré lors de votre KYC, contactez le support.
                    </p>
                </div>

                {{-- Saisie du Montant --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                        Montant à retirer (FCFA) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="1000"
                        max="{{ $user->wallet_available }}" placeholder="Ex: 5000"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">
                    
                    <p class="text-[10px] text-slate-400 font-medium mt-1.5">
                        Min. 1 000 FCFA — Max. {{ number_format($user->wallet_available, 0, ',', ' ') }} FCFA
                    </p>
                    
                    @error('amount')
                        <p class="text-danger text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Détail du virement --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-4 text-xs space-y-2 shadow-xs">
                    <p class="font-black text-slate-900 uppercase tracking-wider text-[11px]">Détail du virement</p>
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Frais de virement Mobile Money</span>
                        <span class="text-primary-600 font-bold">Pris en charge par Ali-Kamer</span>
                    </div>
                    <div class="flex justify-between font-black text-slate-900 border-t border-slate-100 pt-2 text-sm">
                        <span>Vous recevrez exactement</span>
                        <span class="text-primary-600" id="netAmount">—</span>
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <button type="submit"
                    class="w-full bg-accent-500 hover:bg-accent-600 active:scale-98 text-slate-900 font-black
                       py-3.5 rounded-xl transition text-sm shadow-xs uppercase tracking-wider">
                    Confirmer le retrait
                </button>

                <a href="{{ route('seller.wallet.index') }}"
                    class="block text-center text-xs font-bold text-slate-500 hover:text-slate-900 hover:underline pt-1 transition">
                    Annuler et retourner au portefeuille
                </a>
            </form>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.querySelector('input[name="amount"]');
                const display = document.getElementById('netAmount');

                function updateNet() {
                    const net = parseInt(input.value) || 0;
                    if (net < 1000) {
                        display.textContent = '—';
                        return;
                    }
                    display.textContent = new Intl.NumberFormat('fr-FR').format(net) + ' FCFA';
                }

                if (input) {
                    input.addEventListener('input', updateNet);
                }
            });
        </script>
    @endpush
@endsection