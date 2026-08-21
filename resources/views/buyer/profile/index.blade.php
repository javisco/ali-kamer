@extends('base')
@section('title', 'Mon profil')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mon profil</h1>

    {{-- Score de fiabilité --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-4">Score de fiabilité</h2>
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
                    <span class="text-xl font-extrabold text-gray-900">
                        {{ $user->trust_score }}
                    </span>
                </div>
            </div>

            <div>
                <p class="font-bold text-lg
                    {{ $user->trust_score >= 70 ? 'text-emerald-600' :
                       ($user->trust_score >= 40 ? 'text-orange-500' : 'text-red-500') }}">
                    @if($user->trust_score >= 70) Fiable
                    @elseif($user->trust_score >= 40) Moyen
                    @else À améliorer
                    @endif
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Basé sur {{ $reviewsReceived->total() }} avis vendeurs
                </p>
                @if($user->prepayment_required)
                    <div class="bg-orange-50 border border-orange-200 rounded-lg
                                px-3 py-1.5 mt-2 text-xs text-orange-700">
                        ⚠ Prépaiement obligatoire sur vos commandes
                        (score inférieur à 30)
                    </div>
                @endif
                @if($user->purchase_restricted)
                    <div class="bg-red-50 border border-red-200 rounded-lg
                                px-3 py-1.5 mt-2 text-xs text-red-700">
                        ⚠ Achats restreints (plus de 3 litiges abusifs)
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Notes reçues des vendeurs --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <h2 class="font-bold text-gray-800 mb-4">
            Avis reçus des vendeurs
            <span class="text-sm font-normal text-gray-400">
                ({{ $reviewsReceived->total() }})
            </span>
        </h2>

        @if($reviewsReceived->isEmpty())
            <p class="text-gray-400 text-sm">Aucun avis reçu pour l'instant.</p>
        @else
            <div class="space-y-4">
                @foreach($reviewsReceived as $review)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                {{-- Nom de la boutique --}}
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ $review->order->shop->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Commande {{ $review->order->reference }}
                                    — {{ $review->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            {{-- Étoiles --}}
                            <div class="text-yellow-400 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        @if($review->body)
                            <p class="text-sm text-gray-700 italic">
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
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">Historique des transactions</h2>

        @if($transactions->isEmpty())
            <p class="text-gray-400 text-sm">Aucune transaction.</p>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($transactions as $tx)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tx->typeLabel() }}
                            </p>
                            @if($tx->note)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $tx->note }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-300">
                                {{ $tx->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <p class="font-bold text-sm flex-shrink-0 ml-4
                           {{ $tx->isCredit() ? 'text-emerald-600' : 'text-red-500' }}">
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