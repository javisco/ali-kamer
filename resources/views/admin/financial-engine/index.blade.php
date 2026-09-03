@extends('layouts.admin')
@section('title', 'Moteur Financier')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Moteur Financier</h1>
    <p class="text-sm text-gray-500 mb-8">
        Tous les taux sont variables. Modifiez-les ici —
        les nouvelles commandes les appliqueront immédiatement.
    </p>

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

    {{-- ── SIMULATEUR GROSS-UP ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-6 mb-8"
         x-data="grossUpSimulator()">

        <h2 class="font-bold text-gray-800 mb-1">📊 Simulateur Gross-Up</h2>
        <p class="text-xs text-gray-400 mb-5">
            Saisissez un prix produit pour voir ce que chaque partie paie/reçoit
            avec les taux actuels — Gross-Up inclus.
        </p>

        <div class="flex gap-3 mb-6">
            <input type="number"
                   x-model="amount"
                   @input="calculate()"
                   placeholder="Prix article en FCFA"
                   class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                          focus:ring-2 focus:ring-indigo-500">
            <span class="flex items-center text-sm text-gray-500 font-medium">FCFA</span>
        </div>

        <template x-if="result && amount > 0">
            <div class="space-y-4">

                {{-- Acheteur --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-3">
                        🛒 Acheteur
                    </p>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Prix article</span>
                            <span x-text="fmt(result.subtotal)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">
                                + Protection
                                (<span x-text="pct(rates.protection)"></span>)
                            </span>
                            <span class="text-orange-600" x-text="fmt(result.protection)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">
                                + Frais Campay collecte — Gross-Up
                                (<span x-text="pct(rates.collect)"></span>)
                            </span>
                            <span class="text-orange-600" x-text="fmt(result.campayCollect)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-blue-900
                                    border-t border-blue-200 pt-2 mt-1">
                            <span>Total payé par l'acheteur</span>
                            <span x-text="fmt(result.totalAmount)"></span>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 mt-2">
                        ✅ La plateforme reçoit <strong x-text="fmt(result.subtotal + result.protection)"></strong>
                        nets après Campay.
                    </p>
                </div>

                {{-- Vendeur --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wide mb-3">
                        🏪 Vendeur
                    </p>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Prix article</span>
                            <span x-text="fmt(result.subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>
                                − Commission plateforme
                                (<span x-text="pct(rates.commission)"></span>)
                            </span>
                            <span x-text="'−' + fmt(result.commission)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-emerald-900
                                    border-t border-emerald-200 pt-2 mt-1">
                            <span>Reçu exactement sur son MoMo</span>
                            <span class="text-emerald-600" x-text="fmt(result.netSeller)"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 italic">
                            <span>
                                Ali-Kamer envoie à Campay (Gross-Up
                                <span x-text="pct(rates.payout)"></span>)
                            </span>
                            <span x-text="fmt(result.grossSeller)"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 italic">
                            <span>Frais Campay payout (supportés Ali-Kamer)</span>
                            <span x-text="'−' + fmt(result.campayPayout)"></span>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-600 mt-2">
                        ✅ Grâce au Gross-Up, le vendeur reçoit exactement
                        <strong x-text="fmt(result.netSeller)"></strong> FCFA — pas un franc de moins.
                    </p>
                </div>

                {{-- Agence --}}
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-purple-800 uppercase tracking-wide mb-3">
                        🚚 Agence
                    </p>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between font-bold text-purple-900">
                            <span>
                                Reçu exactement sur son MoMo
                                (<span x-text="pct(rates.agency)"></span> du prix)
                            </span>
                            <span class="text-purple-600" x-text="fmt(result.netAgency)"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 italic">
                            <span>
                                Ali-Kamer envoie à Campay (Gross-Up
                                <span x-text="pct(rates.payout)"></span>)
                            </span>
                            <span x-text="fmt(result.grossAgency)"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 italic">
                            <span>Frais Campay payout (supportés Ali-Kamer)</span>
                            <span x-text="'−' + fmt(result.campayAgency)"></span>
                        </div>
                    </div>
                </div>

                {{-- Ali-Kamer --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-3">
                        💰 Marge Ali-Kamer
                    </p>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between text-emerald-600">
                            <span>+ Protection collectée</span>
                            <span x-text="fmt(result.protection)"></span>
                        </div>
                        <div class="flex justify-between text-emerald-600">
                            <span>+ Commission plateforme</span>
                            <span x-text="fmt(result.commission)"></span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>− Frais Campay payout vendeur</span>
                            <span x-text="'−' + fmt(result.campayPayout)"></span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>− Frais Campay payout agence</span>
                            <span x-text="'−' + fmt(result.campayAgency)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-900
                                    border-t border-gray-200 pt-2 mt-1">
                            <span>Marge nette Ali-Kamer</span>
                            <span class="text-indigo-600" x-text="fmt(result.platformNet)"></span>
                        </div>
                    </div>
                </div>

                {{-- Vérification --}}
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-sm">
                    <p class="font-bold text-indigo-800 mb-2">🔍 Vérification équilibre</p>
                    <div class="text-xs text-indigo-700 space-y-1">
                        <div class="flex justify-between">
                            <span>Acheteur paie</span>
                            <span x-text="fmt(result.totalAmount) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>= Vendeur reçoit</span>
                            <span x-text="fmt(result.netSeller) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>+ Agence reçoit</span>
                            <span x-text="fmt(result.netAgency) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>+ Marge Ali-Kamer</span>
                            <span x-text="fmt(result.platformNet) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>+ Frais Campay (collect + payouts)</span>
                            <span x-text="fmt(result.campayCollect + result.campayPayout + result.campayAgency) + ' FCFA'"></span>
                        </div>
                    </div>
                </div>

            </div>
        </template>
    </div>

    {{-- ── FORMULAIRE PARAMÈTRES ─────────────────────────────────── --}}
    <form method="POST" action="{{ route('admin.financial-engine.update') }}">
        @csrf

        @php
            $groupLabels = [
                'campay'      => '🔧 Taux Campay réels (ne pas modifier sans vérifier votre contrat Campay)',
                'commissions' => '💰 Commissions et frais plateforme',
                'timers'      => '⏱ Délais automatiques',
                'limites'     => '🔒 Limites et seuils',
            ];
        @endphp

        @foreach($settings as $group => $items)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="font-bold text-gray-800 mb-1">
                    {{ $groupLabels[$group] ?? $group }}
                </h2>

                @if($group === 'campay')
                    <p class="text-xs text-red-500 mb-4">
                        ⚠ Ces taux sont utilisés pour le calcul Gross-Up.
                        Vérifiez votre contrat Campay avant de les modifier.
                        Une valeur incorrecte peut créer des écarts financiers.
                    </p>
                @endif

                @if($group === 'commissions')
                    <p class="text-xs text-indigo-500 mb-4">
                        Vous pouvez mettre la commission plateforme à 0% pour attirer des vendeurs.
                        Le simulateur ci-dessus montre l'impact en temps réel.
                    </p>
                @endif

                <div class="space-y-5">
                    @foreach($items as $setting)
                        <div class="flex items-start gap-6">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-800 mb-0.5">
                                    {{ $setting->label }}
                                </label>
                                @if($setting->description)
                                    <p class="text-xs text-gray-400">{{ $setting->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <input type="number"
                                       name="settings[{{ $setting->key }}]"
                                       value="{{ $setting->casted_value }}"
                                       step="{{ $setting->type === 'percentage' ? '0.1' : '1' }}"
                                       min="0"
                                       max="{{ $setting->type === 'percentage' ? '100' : '99999999' }}"
                                       class="w-28 border border-gray-300 rounded-xl px-3 py-2
                                              text-sm text-right focus:ring-2 focus:ring-indigo-500">
                                <span class="text-sm text-gray-400 w-8">
                                    {{ $setting->typeLabel() }}
                                </span>
                            </div>
                        </div>
                        @if(! $loop->last)
                            <hr class="border-gray-50">
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                       py-3.5 rounded-2xl transition">
            Sauvegarder les paramètres
        </button>

        <p class="text-xs text-center text-gray-400 mt-4">
            ⚠ Les modifications s'appliquent aux nouvelles commandes uniquement.
            Les commandes existantes conservent leurs taux d'origine (financial_snapshot).
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

        // Taux lus depuis PHP (platform_settings actifs)
        rates: {
            commission: {{ \App\Models\PlatformSetting::getRate('platform_commission_rate') }},
            agency:     {{ \App\Models\PlatformSetting::getRate('agency_commission_rate') }},
            protection: {{ \App\Models\PlatformSetting::getRate('protection_rate') }},
            collect:    {{ \App\Models\PlatformSetting::getRate('campay_collect_rate') }},
            payout:     {{ \App\Models\PlatformSetting::getRate('campay_payout_rate') }},
            fixedFee:   {{ (int) \App\Models\PlatformSetting::getValue('campay_fixed_fee', 0) }},
        },

        calculate() {
            const s = parseInt(this.amount) || 0;
            if (s <= 0) { this.result = null; return; }

            const r = this.rates;

            // ── Côté vendeur ──────────────────────────────────────────
            const commission = Math.round(s * r.commission);
            const netSeller  = s - commission;

            // Gross-Up payout vendeur
            const grossSeller  = r.payout > 0 && r.payout < 1
                ? Math.ceil((netSeller + r.fixedFee) / (1 - r.payout))
                : netSeller + r.fixedFee;
            const campayPayout = grossSeller - netSeller;

            // ── Côté agence ───────────────────────────────────────────
            const netAgency   = Math.round(s * r.agency);
            const grossAgency = r.payout > 0 && r.payout < 1
                ? Math.ceil((netAgency + r.fixedFee) / (1 - r.payout))
                : netAgency + r.fixedFee;
            const campayAgency = grossAgency - netAgency;

            // ── Côté acheteur ─────────────────────────────────────────
            const protection    = Math.round(s * r.protection);
            const wantToReceive = s + protection;
            const totalAmount   = r.collect > 0 && r.collect < 1
                ? Math.ceil((wantToReceive + r.fixedFee) / (1 - r.collect))
                : wantToReceive + r.fixedFee;
            const campayCollect = totalAmount - wantToReceive;

            // ── Marge plateforme ──────────────────────────────────────
            const platformNet = protection + commission - campayPayout - campayAgency;

            this.result = {
                subtotal:    s,
                protection,
                commission,
                campayCollect,
                totalAmount,
                netSeller,
                grossSeller,
                campayPayout,
                netAgency,
                grossAgency,
                campayAgency,
                platformNet,
            };
        },

        // Formater en FCFA
        fmt(n) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
        },

        // Afficher un taux en pourcentage
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