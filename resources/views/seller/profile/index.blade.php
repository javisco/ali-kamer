@extends('layouts.seller')
@section('title', 'Mon Profil & Ma Réputation')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Titre de la page --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#016837]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#016837]"></span>
                    Espace Vendeur
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900">Mon Profil & Ma Réputation</h1>
                <p class="mt-1 text-xs text-slate-500">
                    Consultez vos indicateurs de confiance, votre historique d'évaluations et vos statistiques de boutique.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-2xl bg-emerald-100 border border-emerald-200 px-3 py-1.5 text-xs font-black text-[#016837]">
                    <span class="h-2 w-2 rounded-full bg-[#016837]"></span>
                    Vendeur {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>

        {{-- 1. IDENTITÉ DU VENDEUR & COMPTE --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-[#016837] to-emerald-800 text-white flex items-center justify-center font-black text-2xl shadow-md uppercase shrink-0">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                        @if($shop)
                            <span class="rounded-xl bg-[#016837]/10 text-[#016837] px-3 py-0.5 text-xs font-bold">
                                🏪 {{ $shop->name }}
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 flex flex-wrap gap-4 pt-1">
                        <span>📧 {{ $user->email }}</span>
                        <span>📱 {{ $user->phone ?: ($user->phone_momo ?: 'Non renseigné') }}</span>
                        @if($shop && $shop->city)
                            <span>📍 {{ $shop->city }}</span>
                        @endif
                        <span>🗓 Membre depuis le {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PANNEAU PÉDAGOGIQUE : COMPRENDRE MA RÉPUTATION (Spécification Section 12) --}}
        <div class="rounded-3xl border border-blue-200 bg-blue-50/60 p-6 shadow-xs">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-lg">💡</span>
                <h3 class="text-xs font-black uppercase tracking-wider text-blue-900">Comprendre vos indicateurs Ali-Kamer</h3>
            </div>
            <p class="text-xs text-blue-800 leading-relaxed mb-4">
                Ali-Kamer sépare rigoureusement la <strong>satisfaction des clients</strong> de la <strong>confiance opérationnelle</strong> de votre compte.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-amber-600 mb-1">
                        <span>★</span>
                        <span>NOTE CLIENTS</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Moyenne des évaluations (1 à 5 étoiles) laissées par les acheteurs après réception de leurs articles.
                    </p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-[#016837] mb-1">
                        <span>🛡</span>
                        <span>TRUST SCORE</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Score interne (sur 100) mesurant votre ponctualité de livraison, absence de litiges et respect des règles.
                    </p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-slate-800 mb-1">
                        <span>⚡</span>
                        <span>PROFIL DE RISQUE</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Classification automatique de sécurité interne (LOW, MEDIUM, HIGH) garantissant la protection des transactions.
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. CHIFFRES CLÉS & SCORES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- NOTE MOYENNE --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-slate-400">Note Moyenne Clients</div>
                <div class="mt-3 text-4xl font-black text-slate-900 flex items-center justify-center gap-2">
                    <span>{{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}</span>
                    <span class="text-2xl text-amber-400">★</span>
                </div>
                <div class="mt-2 text-xs font-bold text-slate-500">
                    {{ $reviewsCount }} évaluation(s) reçue(s)
                </div>
            </div>

            {{-- TRUST SCORE --}}
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50/50 p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-emerald-800">TrustScore Ali-Kamer</div>
                <div class="mt-3 text-4xl font-black text-[#016837]">
                    {{ $user->trust_score ?? 50 }}<span class="text-xl text-emerald-600">/100</span>
                </div>
                <div class="mt-2 text-xs font-bold text-emerald-700">
                    Score de fiabilité opérationnelle
                </div>
            </div>

            {{-- RISK PROFILE --}}
            @php
                $riskBadges = [
                    'LOW'      => 'bg-emerald-100 text-[#016837] border-emerald-300',
                    'MEDIUM'   => 'bg-blue-100 text-blue-800 border-blue-300',
                    'HIGH'     => 'bg-orange-100 text-orange-800 border-orange-300',
                    'CRITICAL' => 'bg-red-100 text-red-800 border-red-300',
                ];
            @endphp
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-slate-400">Profil de Risque Interne</div>
                <div class="mt-3">
                    <span class="inline-block rounded-2xl border px-4 py-1.5 text-lg font-black uppercase tracking-wide {{ $riskBadges[$user->risk_profile ?? 'LOW'] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $user->risk_profile ?? 'LOW' }}
                    </span>
                </div>
                <div class="mt-2 text-xs font-bold text-slate-500">
                    Statut de vigilance plateforme
                </div>
            </div>
        </div>

        {{-- 4. RÉSUMÉ EXPLICATIF AUTORISÉ DU TRUSTSCORE (Spécification Section 14) --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-700">
                    Détail explicatif de vos performances
                </h3>
                <span class="text-[11px] text-slate-400">Données transparentes autorisées</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-center">
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-emerald-700">{{ $summary['completed_orders'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Commandes terminées</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-slate-800">{{ $summary['cancelled_orders'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Commandes annulées</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black {{ $summary['disputes_count'] > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $summary['disputes_count'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Litiges déclarés</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black {{ $summary['sanctions_count'] > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $summary['sanctions_count'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Sanctions reçues</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-slate-800">{{ $summary['reviews_count'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Avis clients</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-amber-500">{{ $summary['avg_rating'] > 0 ? $summary['avg_rating'] . '/5' : '—' }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Note moyenne</div>
                </div>
            </div>
        </div>

        {{-- 5. HISTORIQUE DES NOTES DU VENDEUR (Spécification Section 13) --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Historique des Évaluations Reçues</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Détail des avis laissés par vos acheteurs après livraison.</p>
                </div>
                <span class="rounded-xl bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ is_countable($reviewsHistory) ? count($reviewsHistory) : $reviewsHistory->count() }} affiché(s)
                </span>
            </div>

            @if($reviewsHistory->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <div class="text-3xl mb-2">⭐</div>
                    <div class="font-bold text-sm text-slate-700">Aucune évaluation pour le moment</div>
                    <p class="text-xs text-slate-400 mt-1">Les avis apparaîtront ici dès que vos acheteurs auront noté leurs commandes terminées.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Commande</th>
                                <th class="px-6 py-4">Acheteur</th>
                                <th class="px-6 py-4">Produits</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Note</th>
                                <th class="px-6 py-4">Commentaire</th>
                                <th class="px-6 py-4">Statut Commande</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($reviewsHistory as $review)
                                @php
                                    $order = $review->order;
                                    $productNames = $order && $order->items ? $order->items->pluck('product_title')->join(', ') : 'Produit de la boutique';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    {{-- Commande --}}
                                    <td class="px-6 py-4 font-mono font-bold text-slate-800">
                                        @if($order)
                                            <a href="{{ route('seller.orders.show', $order) }}" class="text-[#016837] hover:underline">
                                                #{{ $order->reference }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- Acheteur --}}
                                    <td class="px-6 py-4 font-bold text-slate-900">
                                        {{ $review->reviewer?->name ?? 'Acheteur vérifié' }}
                                    </td>

                                    {{-- Produits --}}
                                    <td class="px-6 py-4 text-slate-600 max-w-[200px] truncate" title="{{ $productNames }}">
                                        {{ $productNames }}
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $review->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- Note --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1 font-black text-amber-500">
                                            <span>{{ $review->rating }}/5</span>
                                            <span>{{ str_repeat('★', $review->rating) }}</span>
                                        </div>
                                    </td>

                                    {{-- Commentaire --}}
                                    <td class="px-6 py-4 text-slate-700 max-w-[280px]">
                                        {{ $review->body ?: 'Aucun commentaire textuel laissé.' }}
                                    </td>

                                    {{-- Statut commande --}}
                                    <td class="px-6 py-4">
                                        <span class="rounded-lg bg-emerald-50 text-[#016837] border border-emerald-200 px-2 py-0.5 text-[10px] font-black uppercase">
                                            {{ $order?->status ?? 'Terminée' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($reviewsHistory, 'hasPages') && $reviewsHistory->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $reviewsHistory->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
