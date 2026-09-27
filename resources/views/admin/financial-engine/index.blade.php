@extends('layouts.admin')

@section('title', 'Moteur Financier - Ali-Kamer')

@section('content')
<div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                        Configuration Réseau & Commissions
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                        Moteur Financier
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm mt-0.5 font-medium">
                        Tous les taux sont variables. Modifiez-les ici — les nouvelles commandes les appliqueront immédiatement.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. MESSAGES DE NOTIFICATION -->
        @if(session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] rounded-xl p-4 text-xs font-bold space-y-1 shadow-xs">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- 3. SIMULATEUR GROSS-UP -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-5 sm:p-6"
             x-data="grossUpSimulator()">

            <div class="flex items-center gap-2 mb-1">
                <span class="p-1.5 rounded-lg bg-[#016837]/10 text-[#016837]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </span>
                <h2 class="font-black text-sm uppercase tracking-wider text-[#0a1b12]">Simulateur Gross-Up</h2>
            </div>
            <p class="text-xs text-gray-400 font-medium mb-5">
                Saisissez un prix produit pour simuler ce que chaque partie paie/reçoit avec les taux actuels — Gross-Up inclus.
            </p>

            <div class="flex gap-3 mb-6">
                <div class="relative flex-1">
                    <input type="number"
                           x-model="amount"
                           @input="calculate()"
                           placeholder="Prix article en FCFA"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-[#0a1b12] transition duration-200 focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                </div>
                <span class="flex items-center text-xs font-black text-gray-500 bg-[#F7F7F2] px-4 rounded-xl border border-gray-200">FCFA</span>
            </div>

            <template x-if="result && amount > 0">
                <div class="space-y-4">

                    {{-- Acheteur --}}
                    <div class="bg-[#F7F7F2] border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-black text-[#0a1b12] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span>🛒</span> Acheteur
                        </p>
                        <div class="space-y-1.5 text-xs font-medium">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Prix article</span>
                                <span class="text-[#0a1b12] font-bold" x-text="fmt(result.subtotal)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    + Protection (<span x-text="pct(rates.protection)"></span>)
                                </span>
                                <span class="text-[#F9A01B] font-bold" x-text="fmt(result.protection)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">
                                    + Frais elgiopay collecte — Gross-Up (<span x-text="pct(rates.collect)"></span>)
                                </span>
                                <span class="text-[#F9A01B] font-bold" x-text="fmt(result.elgiopayCollect)"></span>
                            </div>
                            <div class="flex justify-between font-black text-[#0a1b12] border-t border-gray-200 pt-2 mt-1 text-sm">
                                <span>Total payé par l'acheteur</span>
                                <span x-text="fmt(result.totalAmount)"></span>
                            </div>
                        </div>
                        <p class="text-[11px] text-[#016837] font-bold mt-2.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            La plateforme reçoit <strong class="ml-1" x-text="fmt(result.subtotal + result.protection)"></strong> nets après elgiopay.
                        </p>
                    </div>

                    {{-- Vendeur --}}
                    <div class="bg-[#016837]/5 border border-[#016837]/20 rounded-xl p-4">
                        <p class="text-xs font-black text-[#016837] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span>🏪</span> Vendeur
                        </p>
                        <div class="space-y-1.5 text-xs font-medium">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Prix article</span>
                                <span class="text-[#0a1b12] font-bold" x-text="fmt(result.subtotal)"></span>
                            </div>
                            <div class="flex justify-between text-[#E30613]">
                                <span>
                                    − Commission plateforme (<span x-text="pct(rates.commission)"></span>)
                                </span>
                                <span class="font-bold" x-text="'−' + fmt(result.commission)"></span>
                            </div>
                            <div class="flex justify-between font-black text-[#016837] border-t border-[#016837]/20 pt-2 mt-1 text-sm">
                                <span>Reçu exactement sur son MoMo</span>
                                <span x-text="fmt(result.netSeller)"></span>
                            </div>
                            <div class="flex justify-between text-[11px] text-gray-400 italic">
                                <span>
                                    Ali-Kamer envoie à elgiopay (Gross-Up <span x-text="pct(rates.payout)"></span>)
                                </span>
                                <span x-text="fmt(result.grossSeller)"></span>
                            </div>
                            <div class="flex justify-between text-[11px] text-gray-400 italic">
                                <span>Frais elgiopay payout (supportés Ali-Kamer)</span>
                                <span x-text="'−' + fmt(result.elgiopayPayout)"></span>
                            </div>
                        </div>
                        <p class="text-[11px] text-[#016837] font-bold mt-2.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Grâce au Gross-Up, le vendeur reçoit exactement <strong class="mx-1" x-text="fmt(result.netSeller)"></strong> — pas un franc de moins.
                        </p>
                    </div>

                    {{-- Agence --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-xs font-black text-[#0a1b12] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span>🚚</span> Agence
                        </p>
                        <div class="space-y-1.5 text-xs font-medium">
                            <div class="flex justify-between font-bold text-[#0a1b12]">
                                <span>
                                    Reçu exactement sur son MoMo (<span x-text="pct(rates.agency)"></span> du prix)
                                </span>
                                <span class="text-[#016837]" x-text="fmt(result.netAgency)"></span>
                            </div>
                            <div class="flex justify-between text-[11px] text-gray-400 italic">
                                <span>
                                    Ali-Kamer envoie à elgiopay (Gross-Up <span x-text="pct(rates.payout)"></span>)
                                </span>
                                <span x-text="fmt(result.grossAgency)"></span>
                            </div>
                            <div class="flex justify-between text-[11px] text-gray-400 italic">
                                <span>Frais elgiopay payout (supportés Ali-Kamer)</span>
                                <span x-text="'−' + fmt(result.elgiopayAgency)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Ali-Kamer --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-xs">
                        <p class="text-xs font-black text-[#0a1b12] uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <span>💰</span> Marge Ali-Kamer
                        </p>
                        <div class="space-y-1.5 text-xs font-medium">
                            <div class="flex justify-between text-[#016837]">
                                <span>+ Protection collectée</span>
                                <span class="font-bold" x-text="fmt(result.protection)"></span>
                            </div>
                            <div class="flex justify-between text-[#016837]">
                                <span>+ Commission plateforme</span>
                                <span class="font-bold" x-text="fmt(result.commission)"></span>
                            </div>
                            <div class="flex justify-between text-[#E30613]">
                                <span>− Frais elgiopay payout vendeur</span>
                                <span class="font-bold" x-text="'−' + fmt(result.elgiopayPayout)"></span>
                            </div>
                            <div class="flex justify-between text-[#E30613]">
                                <span>− Frais elgiopay payout agence</span>
                                <span class="font-bold" x-text="'−' + fmt(result.elgiopayAgency)"></span>
                            </div>
                            <div class="flex justify-between font-black text-[#0a1b12] border-t border-gray-100 pt-2 mt-1 text-sm">
                                <span>Marge nette Ali-Kamer</span>
                                <span class="text-[#016837]" x-text="fmt(result.platformNet)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Vérification --}}
                    <div class="bg-[#016837]/10 border border-[#016837]/20 rounded-xl p-4 text-xs">
                        <p class="font-black text-[#016837] mb-2 uppercase tracking-wider text-[11px]">🔍 Vérification équilibre</p>
                        <div class="text-xs text-[#0a1b12] font-semibold space-y-1">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Acheteur paie</span>
                                <span x-text="fmt(result.totalAmount) + ' FCFA'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">= Vendeur reçoit</span>
                                <span x-text="fmt(result.netSeller) + ' FCFA'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">+ Agence reçoit</span>
                                <span x-text="fmt(result.netAgency) + ' FCFA'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">+ Marge Ali-Kamer</span>
                                <span x-text="fmt(result.platformNet) + ' FCFA'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">+ Frais elgiopay (collect + payouts)</span>
                                <span x-text="fmt(result.elgiopayCollect + result.elgiopayPayout + result.elgiopayAgency) + ' FCFA'"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </div>

        <!-- 4. FORMULAIRE PARAMÈTRES -->
        <form method="POST" action="{{ route('admin.financial-engine.update') }}" class="space-y-5">
            @csrf

            @php
                $groupLabels = [
                    'elgiopay'      => '🔧 Taux elgiopay réels (ne pas modifier sans vérifier votre contrat elgiopay)',
                    'commissions' => '💰 Commissions et frais plateforme',
                    'timers'      => '⏱ Délais automatiques',
                    'limites'     => '🔒 Limites et seuils',
                    'variantes'   => '🧩 Limites des variantes produits',
                ];
            @endphp

            @foreach($settings as $group => $items)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-5 sm:p-6">
                    <h2 class="font-black text-xs uppercase tracking-wider text-[#0a1b12] mb-1">
                        {{ $groupLabels[$group] ?? $group }}
                    </h2>

                    @if($group === 'elgiopay')
                        <p class="text-xs text-[#E30613] font-medium mb-4 flex items-center gap-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Ces taux sont utilisés pour le calcul Gross-Up. Vérifiez votre contrat elgiopay avant modification.
                        </p>
                    @endif

                    @if($group === 'commissions')
                        <p class="text-xs text-[#016837] font-medium mb-4">
                            Vous pouvez configurer la commission à 0% pour attirer les vendeurs. Le simulateur indique l'impact en temps réel.
                        </p>
                    @endif

                    <div class="space-y-4 divide-y divide-gray-100">
                        @foreach($items as $setting)
                            <div class="flex items-center justify-between gap-4 pt-3 first:pt-0">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-[#0a1b12]">
                                        {{ $setting->label }}
                                    </label>
                                    @if($setting->description)
                                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $setting->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <input type="number"
                                           name="settings[{{ $setting->key }}]"
                                           value="{{ $setting->casted_value }}"
                                           step="{{ $setting->type === 'percentage' ? '0.1' : '1' }}"
                                           min="0"
                                           max="{{ $setting->type === 'percentage' ? '100' : '99999999' }}"
                                           class="w-28 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-right text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                                    <span class="text-xs font-bold text-gray-400 w-8">
                                        {{ $setting->typeLabel() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit"
                    class="w-full bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] font-black py-4 rounded-xl transition text-xs shadow-xs uppercase tracking-wider">
                Sauvegarder la configuration financière
            </button>

            <p class="text-[11px] text-center text-gray-400 font-medium mt-3">
                ⚠ Les modifications s'appliquent aux nouvelles commandes uniquement. Les commandes existantes conservent leur snapshot financier.
            </p>
        </form>

    </div>
</div>

@push('scripts')
<script>
function grossUpSimulator() {
    return {
        amount: 10000,
        result: null,

        rates: {
            commission: {{ \App\Models\PlatformSetting::getRate('platform_commission_rate') }},
            agency:     {{ \App\Models\PlatformSetting::getRate('agency_commission_rate') }},
            protection: {{ \App\Models\PlatformSetting::getRate('protection_rate') }},
            collect:    {{ \App\Models\PlatformSetting::getRate('gateway_collect_rate') }},
            payout:     {{ \App\Models\PlatformSetting::getRate('gateway_payout_rate') }},
            fixedFee:   {{ (int) \App\Models\PlatformSetting::getValue('elgiopay_fixed_fee', 0) }},
        },

        calculate() {
            const s = parseInt(this.amount) || 0;
            if (s <= 0) { this.result = null; return; }

            const r = this.rates;

            const commission = Math.round(s * r.commission);
            const netSeller  = s - commission;

            const grossSeller  = r.payout > 0 && r.payout < 1
                ? Math.ceil((netSeller + r.fixedFee) / (1 - r.payout))
                : netSeller + r.fixedFee;
            const elgiopayPayout = grossSeller - netSeller;

            const netAgency   = Math.round(s * r.agency);
            const grossAgency = r.payout > 0 && r.payout < 1
                ? Math.ceil((netAgency + r.fixedFee) / (1 - r.payout))
                : netAgency + r.fixedFee;
            const elgiopayAgency = grossAgency - netAgency;

            const protection    = Math.round(s * r.protection);
            const wantToReceive = s + protection;
            const totalAmount   = r.collect > 0 && r.collect < 1
                ? Math.ceil((wantToReceive + r.fixedFee) / (1 - r.collect))
                : wantToReceive + r.fixedFee;
            const elgiopayCollect = totalAmount - wantToReceive;

            const platformNet = protection + commission - elgiopayPayout - elgiopayAgency;

            this.result = {
                subtotal:    s,
                protection,
                commission,
                elgiopayCollect,
                totalAmount,
                netSeller,
                grossSeller,
                elgiopayPayout,
                netAgency,
                grossAgency,
                elgiopayAgency,
                platformNet,
            };
        },

        fmt(n) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
        },

        pct(rate) {
            return (rate * 100).toFixed(1).replace('.0', '') + '%';
        },

        init() {
            this.calculate();
        }
    }
}
</script>
@endpush
@endsection