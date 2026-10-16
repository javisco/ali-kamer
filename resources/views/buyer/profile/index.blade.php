@extends('layouts.buyer')
@section('title', 'Mon profil')

@php
    // ── Statut du compte ────────────────────────────────────────────────
    $accountStatusLabel = [
        'active'    => 'Actif',
        'candidate' => 'En attente',
        'suspended' => 'Suspendu',
        'banned'    => 'Banni',
    ][$user->status] ?? ucfirst($user->status);

    // ── RiskProfile → libellé "grand public" (aucune règle interne exposée)
    $risk = $user->risk_profile ?? 'clean';
    $riskMap = [
        'clean'      => ['label' => 'Compte en règle',              'class' => 'bg-emerald-100 text-[#016837] border-emerald-300'],
        'watch'      => ['label' => 'Compte sous surveillance',     'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
        'restricted' => ['label' => 'Fonctionnalités limitées',     'class' => 'bg-orange-100 text-orange-800 border-orange-300'],
        'blocked'    => ['label' => 'Compte bloqué',                'class' => 'bg-red-100 text-red-800 border-red-300'],
    ];
    $riskBadge = $riskMap[$risk] ?? $riskMap['clean'];

    // ── Libellés d'événements de réputation affichables ────────────────
    $eventLabels = [
        'order_completed'        => 'Commande terminée',
        'order_completed_fast'   => 'Commande terminée rapidement',
        'dispute_won'            => 'Litige résolu en votre faveur',
        'dispute_lost'           => 'Litige perdu',
        'kyc_approved'           => 'Compte vérifié',
        'buyer_reliable'         => 'Réputation confirmée',
        'order_cancelled'        => 'Commande annulée',
        'order_cancelled_repeat' => 'Annulations répétées',
        'milestone_10_orders'    => 'Palier atteint : 10 commandes',
        'milestone_20_orders'    => 'Palier atteint : 20 commandes',
        'milestone_50_orders'    => 'Palier atteint : 50 commandes',
        'blacklist_lifted'       => 'Restriction levée',
    ];

    // ── Helpers d'affichage des statuts de commande ────────────────────
    $orderStatusLabel = fn ($status) => match ($status) {
        \App\Models\Order::STATUS_PENDING => 'En attente',
        \App\Models\Order::STATUS_AWAITING_PAYMENT => 'Paiement requis',
        \App\Models\Order::STATUS_PAID => 'Payée',
        \App\Models\Order::STATUS_PREPARING => 'En préparation',
        \App\Models\Order::STATUS_REGISTERED_ORIGIN => 'Au départ',
        \App\Models\Order::STATUS_IN_TRANSIT => 'En transit',
        \App\Models\Order::STATUS_ARRIVED_DESTINATION => 'Arrivée',
        \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION => 'Retrait requis',
        \App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_AUTO_COMPLETED => 'Terminée',
        \App\Models\Order::STATUS_DISPUTED => 'Litige',
        \App\Models\Order::STATUS_CANCELLED => 'Annulée',
        \App\Models\Order::STATUS_FAILED => 'Échec',
        default => ucfirst(str_replace('_', ' ', $status)),
    };

    $orderStatusClass = fn ($status) => match ($status) {
        \App\Models\Order::STATUS_AWAITING_PAYMENT,
        \App\Models\Order::STATUS_REGISTERED_ORIGIN,
        \App\Models\Order::STATUS_ARRIVED_DESTINATION,
        \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION => 'bg-[#F9A01B]/15 text-[#9a6400]',
        \App\Models\Order::STATUS_PAID,
        \App\Models\Order::STATUS_PREPARING,
        \App\Models\Order::STATUS_IN_TRANSIT,
        \App\Models\Order::STATUS_COMPLETED,
        \App\Models\Order::STATUS_AUTO_COMPLETED => 'bg-[#016837]/10 text-[#016837]',
        \App\Models\Order::STATUS_DISPUTED => 'bg-[#E30613]/10 text-[#E30613]',
        default => 'bg-slate-100 text-slate-500',
    };

    $paymentStatusLabel = fn ($status) => match ($status) {
        'succeeded' => 'Payé',
        'pending'   => 'En attente',
        'failed'    => 'Échoué',
        'refunded'  => 'Remboursé',
        null        => 'Non payé',
        default     => ucfirst(str_replace('_', ' ', (string) $status)),
    };
@endphp

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── TITRE ───────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#016837]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#016837]"></span>
                    Espace Acheteur
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900">Mon profil</h1>
                <p class="mt-1 text-xs text-slate-500">
                    Gérez vos informations, suivez votre activité et comprenez vos indicateurs de confiance.
                </p>
            </div>
            <a href="{{ route('buyer.profile.edit') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#016837] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#015c30]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier mon profil
            </a>
        </div>

        {{-- ── 1. IDENTITÉ ─────────────────────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-[#016837] to-emerald-800 text-white flex items-center justify-center font-black text-2xl shadow-md uppercase shrink-0">
                    {{ \Illuminate\Support\Str::substr($user->name, 0, 2) }}
                </div>
                <div class="flex-1 space-y-2">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                        <span class="rounded-xl bg-emerald-100 border border-emerald-200 px-3 py-0.5 text-xs font-bold text-[#016837]">
                            Acheteur {{ $accountStatusLabel }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 flex flex-wrap gap-4 pt-1">
                        <span>📧 {{ $user->email ?: 'Non renseigné' }}</span>
                        <span>📱 {{ $user->phone ?: 'Non renseigné' }}</span>
                        <span>🗓 Membre depuis le {{ $user->created_at?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-1 text-[11px] font-bold">
                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1
                            {{ $user->email_verified_at ? 'bg-emerald-50 text-[#016837]' : 'bg-orange-50 text-orange-700' }}">
                            {{ $user->email_verified_at ? '✓ Email vérifié' : '⚠ Email non vérifié' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1
                            {{ $user->phone_verified_at ? 'bg-emerald-50 text-[#016837]' : 'bg-slate-100 text-slate-500' }}">
                            {{ $user->phone_verified_at ? '✓ Téléphone vérifié' : 'Téléphone non vérifié' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── 2. ALERTE RESTRICTIONS (statut réel) ────────────────────── --}}
        @if(! empty($restrictions) || $user->prepayment_required || $user->purchase_restricted)
            <div class="rounded-3xl border border-orange-200 bg-orange-50/70 p-5 shadow-xs">
                <div class="flex items-start gap-3">
                    <span class="text-xl">⚠️</span>
                    <div class="text-xs text-orange-900 space-y-1">
                        <p class="font-black uppercase tracking-wide">État de votre compte</p>
                        @if($user->prepayment_required)
                            <p>Un <strong>prépaiement</strong> est exigé sur vos nouvelles commandes.</p>
                        @endif
                        @if($user->purchase_restricted)
                            <p>Vos <strong>achats sont temporairement restreints</strong> suite à des litiges répétés.</p>
                        @endif
                        @if(! empty($restrictions))
                            <p>Certaines fonctionnalités sont limitées : {{ implode(', ', $restrictions) }}.</p>
                        @endif
                        <p class="text-orange-700/80 pt-1">
                            Pour toute question, contactez le support depuis le
                            <a href="{{ route('help') }}" class="underline font-bold">centre d'aide</a>.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── 3. PANNEAU PÉDAGOGIQUE : NOTE / TRUSTSCORE / RISKPROFILE ── --}}
        <div class="rounded-3xl border border-blue-200 bg-blue-50/60 p-6 shadow-xs">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-lg">💡</span>
                <h3 class="text-xs font-black uppercase tracking-wider text-blue-900">Comprendre vos indicateurs Ali-Kamer</h3>
            </div>
            <p class="text-xs text-blue-800 leading-relaxed mb-4">
                Ali-Kamer distingue rigoureusement la <strong>satisfaction exprimée par les vendeurs</strong>
                de la <strong>confiance opérationnelle</strong> calculée par la plateforme.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-amber-600 mb-1"><span>★</span><span>NOTE REÇUE</span></div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Moyenne des évaluations (1 à 5 étoiles) laissées par les vendeurs après vos commandes.
                    </p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-[#016837] mb-1"><span>🛡</span><span>TRUST SCORE</span></div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Score interne (sur 100) mesurant le respect des règles : commandes honorées, absence d'abus et régularité.
                        Il n'est pas la moyenne de vos notes.
                    </p>
                </div>
                <div class="rounded-2xl bg-white p-4 border border-blue-100 shadow-2xs">
                    <div class="flex items-center gap-2 font-black text-slate-800 mb-1"><span>⚡</span><span>ÉTAT DU COMPTE</span></div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Niveau de vigilance interne garantissant la sécurité des transactions
                        (compte en règle, sous surveillance, limité ou bloqué).
                    </p>
                </div>
            </div>
        </div>

        {{-- ── 4. RÉPUTATION ───────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50/50 p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-emerald-800">TrustScore Ali-Kamer</div>
                <div class="mt-3 text-4xl font-black text-[#016837]">
                    {{ $user->trust_score ?? 0 }}<span class="text-xl text-emerald-600">/100</span>
                </div>
                <div class="mt-2 text-xs font-bold text-emerald-700">Score de fiabilité opérationnelle</div>
                @if($lastTrustUpdate)
                    <div class="mt-1 text-[11px] text-emerald-700/70">
                        Mis à jour le {{ \Illuminate\Support\Carbon::parse($lastTrustUpdate)->format('d/m/Y') }}
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-slate-400">Note reçue des vendeurs</div>
                <div class="mt-3 text-4xl font-black text-slate-900 flex items-center justify-center gap-2">
                    <span>{{ $stats['avg_rating_received'] > 0 ? number_format($stats['avg_rating_received'], 1) : '—' }}</span>
                    <span class="text-2xl text-amber-400">★</span>
                </div>
                <div class="mt-2 text-xs font-bold text-slate-500">
                    {{ $stats['reviews_received'] }} évaluation(s) reçue(s)
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs text-center">
                <div class="text-[11px] font-black uppercase tracking-widest text-slate-400">État de vigilance</div>
                <div class="mt-3">
                    <span class="inline-block rounded-2xl border px-4 py-1.5 text-sm font-black {{ $riskBadge['class'] }}">
                        {{ $riskBadge['label'] }}
                    </span>
                </div>
                <div class="mt-2 text-xs font-bold text-slate-500">Statut de sécurité plateforme</div>
            </div>
        </div>

        {{-- ── 5. ACTIVITÉ ─────────────────────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-700">Mon activité</h3>
                <a href="{{ route('buyer.orders.index') }}" class="text-[11px] font-bold text-[#016837] hover:underline">
                    Voir toutes mes commandes →
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-center">
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-slate-900">{{ $stats['orders_total'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Commandes</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-emerald-700">{{ $stats['orders_completed'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Terminées</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-[#F9A01B]">{{ $stats['orders_in_progress'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">En cours</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black text-slate-700">{{ $stats['orders_cancelled'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Annulées</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <div class="text-2xl font-black {{ $stats['orders_disputed'] > 0 ? 'text-red-600' : 'text-slate-700' }}">{{ $stats['orders_disputed'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase mt-1">Litiges</div>
                </div>
            </div>
        </div>

        {{-- ── 6. ACCÈS RAPIDES ────────────────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-4">Accès rapides</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <a href="{{ route('buyer.orders.index') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">📦</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes commandes</div>
                </a>
                <a href="{{ route('buyer.disputes.index') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">⚖️</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes litiges</div>
                </a>
                <a href="{{ route('buyer.wallet.history') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">💳</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes transactions</div>
                </a>
                <a href="{{ route('notifications.index') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">🔔</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes notifications</div>
                </a>
                <a href="{{ route('buyer.profile.edit') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">👤</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes informations</div>
                </a>
                <a href="{{ route('buyer.profile.edit') }}#securite"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">🔒</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Sécurité du compte</div>
                </a>
                <a href="{{ route('buyer.wishlist.index') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">❤️</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes favoris</div>
                </a>
                <a href="{{ route('messaging.index') }}"
                   class="group rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-[#016837] hover:bg-emerald-50/50">
                    <div class="text-lg">💬</div>
                    <div class="mt-1 text-xs font-black text-slate-800 group-hover:text-[#016837]">Mes messages</div>
                </a>
            </div>
            <p class="mt-4 text-[11px] text-slate-400">
                ℹ️ Les adresses de livraison sont renseignées au moment de chaque commande ; elles sont conservées
                à l'identique dans l'historique de la commande concernée.
            </p>
        </div>

        {{-- ── 7. COMMANDES RÉCENTES ───────────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Commandes récentes</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Aperçu de vos 5 dernières commandes.</p>
                </div>
                <a href="{{ route('buyer.orders.index') }}" class="text-[11px] font-bold text-[#016837] hover:underline">Tout voir</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <div class="text-3xl mb-2">📦</div>
                    <div class="font-bold text-sm text-slate-700">Aucune commande pour le moment</div>
                    <a href="{{ route('buyer.home') }}" class="inline-block mt-3 text-xs font-bold text-[#016837] hover:underline">
                        Découvrir la marketplace →
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Référence</th>
                                <th class="px-6 py-4">Boutique</th>
                                <th class="px-6 py-4">Montant</th>
                                <th class="px-6 py-4">Statut</th>
                                <th class="px-6 py-4">Paiement</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-slate-800">{{ $order->reference }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $order->shop->name ?? 'Boutique supprimée' }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-900">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-[9px] font-black uppercase {{ $orderStatusClass($order->status) }}">
                                            {{ $orderStatusLabel($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $paymentStatusLabel($order->payment?->status) }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ $order->created_at?->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('buyer.orders.show', $order) }}" class="font-bold text-[#016837] hover:underline">Détails</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── 8. ÉVALUATIONS REÇUES ───────────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Évaluations reçues des vendeurs</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Avis laissés par les vendeurs à votre sujet.</p>
                </div>
                <span class="rounded-xl bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ $stats['reviews_received'] }}
                </span>
            </div>

            @if($reviewsReceived->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <div class="text-3xl mb-2">⭐</div>
                    <div class="font-bold text-sm text-slate-700">Aucune évaluation reçue</div>
                    <p class="text-xs text-slate-400 mt-1">Les évaluations apparaîtront ici après vos transactions terminées.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($reviewsReceived as $review)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-slate-900">
                                        {{ $review->order->shop->name ?? 'Boutique' }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        @if($review->order)
                                            Commande {{ $review->order->reference }} —
                                        @endif
                                        {{ $review->created_at?->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-amber-400 text-lg shrink-0">
                                    {{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}
                                </div>
                            </div>
                            @if($review->body)
                                <p class="mt-2 text-sm text-slate-700 italic">"{{ $review->body }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($reviewsReceived->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $reviewsReceived->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ── 9. HISTORIQUE DE RÉPUTATION ─────────────────────────────── --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Historique de réputation</h3>
                <p class="text-xs text-slate-400 mt-0.5">Événements ayant contribué à votre score de confiance.</p>
            </div>

            @if($trustEvents->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <div class="text-3xl mb-2">🛡️</div>
                    <div class="font-bold text-sm text-slate-700">Aucun événement enregistré</div>
                    <p class="text-xs text-slate-400 mt-1">
                        Votre historique se construira au fil de vos commandes et transactions.
                    </p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($trustEvents as $event)
                        @php
                            $positive = $event->direction === 'increase';
                        @endphp
                        <div class="flex items-center justify-between gap-4 p-5">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                    {{ $positive ? 'bg-emerald-50 text-[#016837]' : 'bg-red-50 text-red-600' }}">
                                    {{ $positive ? '▲' : '▼' }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $eventLabels[$event->type] ?? 'Mise à jour de la réputation' }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">{{ $event->created_at?->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black {{ $positive ? 'text-[#016837]' : 'text-red-600' }}">
                                    {{ $positive ? '+' : '−' }}{{ abs((int) ($event->trust_after - $event->trust_before)) }}
                                </p>
                                <p class="text-[10px] text-slate-400">{{ $event->trust_before }} → {{ $event->trust_after }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    <p class="text-[11px] text-slate-400">
                        Seuls les événements importants et non sensibles sont affichés. Ce score ne peut pas être modifié
                        manuellement ; toute correction passe par l'équipe Ali-Kamer.
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
