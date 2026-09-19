@extends('layouts.buyer')
@section('title', 'Mon profil')
@section('content')
<div class="bg-slate-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Mon profil</h1>

    {{-- Score de fiabilité --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-4">Score de fiabilité</h2>
        <div class="flex items-center gap-6">

            {{-- Jauge circulaire simple --}}
            <div class="relative w-24 h-24 flex-shrink-0">
                <svg class="w-24 h-24 -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.9" fill="none"
                            stroke="#F3F4F6" stroke-width="3"/>
                    <circle cx="18" cy="18" r="15.9" fill="none"
                            stroke="{{ $user->trust_score >= 70 ? '#10B981' :
                                      ($user->trust_score >= 40 ? '#F59E0B' : '#EF4444') }}"
                            stroke-width="3"
                            stroke-dasharray="{{ $user->trust_score }}, 100"
                            stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xl font-extrabold text-slate-900">
                        {{ $user->trust_score }}
                    </span>
                </div>
            </div>

            <div>
                <p class="font-bold text-lg
                    {{ $user->trust_score >= 70 ? 'text-success' :
                       ($user->trust_score >= 40 ? 'text-accent-500' : 'text-danger') }}">
                    @if($user->trust_score >= 70) Fiable
                    @elseif($user->trust_score >= 40) Moyen
                    @else À améliorer
                    @endif
                </p>
                <p class="text-sm text-slate-500 mt-1">
                    Basé sur {{ $reviewsReceived->total() }} avis vendeurs
                </p>
                @if($user->prepayment_required)
                    <div class="bg-accent-50 border border-accent-200 rounded-lg
                                px-3 py-1.5 mt-2 text-xs text-accent-700">
                        ⚠ Prépaiement obligatoire sur vos commandes
                        (score inférieur à 30)
                    </div>
                @endif
                @if($user->purchase_restricted)
                    <div class="bg-danger-50 border border-danger-200 rounded-lg
                                px-3 py-1.5 mt-2 text-xs text-danger-700">
                        ⚠ Achats restreints (plus de 3 litiges abusifs)
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Notes reçues des vendeurs --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">
        <h2 class="font-bold text-slate-800 mb-4">
            Avis reçus des vendeurs
            <span class="text-sm font-normal text-slate-400">
                ({{ $reviewsReceived->total() }})
            </span>
        </h2>

        @if($reviewsReceived->isEmpty())
            <p class="text-slate-400 text-sm">Aucun avis reçu pour l'instant.</p>
        @else
            <div class="space-y-4">
                @foreach($reviewsReceived as $review)
                    <div class="border border-slate-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                {{-- Nom de la boutique --}}
                                <p class="font-semibold text-slate-900 text-sm">
                                    {{ $review->order->shop->name }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Commande {{ $review->order->reference }}
                                    — {{ $review->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            {{-- Étoiles --}}
                            <div class="text-warning text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        @if($review->body)
                            <p class="text-sm text-slate-700 italic">
                                "{{ $review->body }}"
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $reviewsReceived->links() }}</div>
        @endif
    </div>

    {{-- Historique transactions --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <h2 class="font-bold text-slate-800 mb-4">Historique des transactions</h2>

        @if($transactions->isEmpty())
            <p class="text-slate-400 text-sm">Aucune transaction.</p>
        @else
            <div class="divide-y divide-slate-50">
                @foreach($transactions as $tx)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-slate-900">
                                {{ $tx->typeLabel() }}
                            </p>
                            @if($tx->note)
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $tx->note }}
                                </p>
                            @endif
                            <p class="text-xs text-slate-300">
                                {{ $tx->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <p class="font-bold text-sm flex-shrink-0 ml-4
                           {{ $tx->isCredit() ? 'text-success' : 'text-danger' }}">
                            {{ $tx->isCredit() ? '+' : '-' }}
                            {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $transactions->links() }}</div>
        @endif
    </div>

</div>
</div>
@endsection