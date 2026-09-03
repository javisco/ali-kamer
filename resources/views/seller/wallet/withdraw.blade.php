@extends('layouts.seller')

@section('title', 'Retrait Mobile Money')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-md mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Retrait Mobile Money</h1>
            <p class="text-sm text-gray-500 mb-6">Traitement sous 24h ouvrables.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Solde disponible --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 mb-6">
                <p class="text-sm text-indigo-600 mb-1">Solde disponible</p>
                <p class="text-3xl font-extrabold text-indigo-700">
                    {{ number_format($user->wallet_available, 0, ',', ' ') }}
                    <span class="text-base font-semibold">FCFA</span>
                </p>
            </div>

            <form method="POST" action="{{ route('seller.wallet.withdraw.post') }}" class="space-y-5">
                @csrf

                {{-- Numéro MoMo --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-3">Numéro de réception</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Opérateur</span>
                            <span class="font-semibold uppercase">{{ $user->momo_operator }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Numéro</span>
                            <span class="font-semibold">+237 {{ $user->phone_momo }}</span>
                        </div>
                    </div>
                    {{-- Le numéro MoMo est celui enregistré au KYC — non modifiable ici --}}
                    <p class="text-xs text-gray-400 mt-3">
                        Pour modifier votre numéro MoMo, contactez le support.
                    </p>
                </div>

                {{-- Montant --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Montant à retirer (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="1000"
                        max="{{ $user->wallet_available }}" placeholder="Ex: 5000"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">
                        Minimum 1 000 FCFA — Maximum {{ number_format($user->wallet_available, 0, ',', ' ') }} FCFA
                    </p>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Frais --}}
                {{-- <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 text-sm space-y-2">
                    <div class="flex justify-between text-gray-500">
                        <span>Frais retrait MoMo (1%)</span> --}}
                {{-- Les frais sont déduits automatiquement par Campay --}}
                <span>Déduits automatiquement</span>
                {{-- </div>
                    <div class="flex justify-between font-bold text-gray-800 border-t border-gray-200 pt-2">
                        <span>Vous recevrez environ</span>
                        <span class="text-indigo-600" id="netAmount">—</span>
                    </div>
                </div> --}}

                {{-- Simulateur Gross-Up retrait --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 text-sm space-y-2">
                    <p class="font-medium text-gray-700">Détail du virement</p>
                    <div class="flex justify-between text-gray-500">
                        <span>Frais de virement Mobile Money</span>
                        <span class="text-emerald-600 font-medium">Pris en charge par Ali-Kamer</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 border-t border-gray-200 pt-2">
                        <span>Vous recevrez exactement</span>
                        <span class="text-indigo-600" id="netAmount">—</span>
                    </div>
                </div>

                @push('scripts')
                    <script>
                        const payoutRate = {{ \App\Models\PlatformSetting::getRate('campay_payout_rate') }};
                        const fixedFee = {{ (int) \App\Models\PlatformSetting::getValue('campay_fixed_fee', 0) }};
                        const input = document.querySelector('input[name="amount"]');
                        const display = document.getElementById('netAmount');

                        function updateNet() {
                            const net = parseInt(input.value) || 0;
                            if (net < 1000) {
                                display.textContent = '—';
                                return;
                            }

                            // Le montant saisi = montant NET que le vendeur reçoit
                            // La plateforme calcule le Gross-Up en interne
                            display.textContent = new Intl.NumberFormat('fr-FR').format(net) + ' FCFA';
                        }

                        input.addEventListener('input', updateNet);
                    </script>
                @endpush

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-4 rounded-2xl transition text-sm">
                    Confirmer le retrait
                </button>

                <a href="{{ route('seller.wallet.index') }}"
                    class="block text-center text-sm text-gray-500 hover:underline">
                    Annuler
                </a>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Calcul en temps réel du montant net après frais MoMo (1%)
            const input = document.querySelector('input[name="amount"]');
            const net = document.getElementById('netAmount');

            function update() {
                const amount = parseInt(input.value) || 0;
                if (amount < 1000) {
                    net.textContent = '—';
                    return;
                }
                const fees = Math.round(amount * 0.01);
                const result = amount - fees;
                net.textContent = new Intl.NumberFormat('fr-FR').format(result) + ' FCFA';
            }

            input.addEventListener('input', update);
        </script>
    @endpush
@endsection
