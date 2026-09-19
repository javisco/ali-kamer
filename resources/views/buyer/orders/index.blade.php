@extends('layouts.buyer')
@section('title', 'Mes commandes')

@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            {{-- En-tête de la page --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-primary-600"></div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight">Mes commandes</h1>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-200/60 px-3.5 py-1.5 rounded-xl">
                    {{ $orders->total() ?? $orders->count() }} commande(s)
                </span>
            </div>

            @if (session('success'))
                <div class="bg-success-50 border border-success-200 text-primary-600 px-5 py-3.5 rounded-2xl mb-6 text-xs font-bold flex items-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mb-1">Aucune commande effectuée</h2>
                    <p class="text-slate-500 text-xs mb-6">Vous n'avez pas encore passé de commande sur Ali-Kamer.</p>
                    <a href="{{ route('buyer.home') }}"
                        class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-md shadow-primary-600/20">
                        <span>Explorer le catalogue</span>
                        <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        @php
                            // Config pour les statuts individuels de livraisons/commandes
                            $statusConfig = [
                                'pending' => ['label' => 'En attente', 'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
                                'awaiting_payment' => ['label' => 'Paiement requis', 'class' => 'bg-warning-50 text-warning-700 border-warning-200'],
                                'paid' => ['label' => 'Payé', 'class' => 'bg-success-50 text-primary-600 border-success-200'],
                                'preparing' => ['label' => 'En préparation', 'class' => 'bg-success-50 text-primary-600 border-success-200'],
                                'registered_origin' => ['label' => 'Déposé en agence', 'class' => 'bg-success-50 text-primary-600 border-success-200'],
                                'in_transit' => ['label' => 'En transit', 'class' => 'bg-primary-50 text-primary-700 border-primary-200'],
                                'arrived_destination' => ['label' => 'Arrivé', 'class' => 'bg-primary-50 text-primary-700 border-primary-200'],
                                'awaiting_buyer_confirmation' => ['label' => 'À retirer', 'class' => 'bg-warning-50 text-accent-500 border-warning-200'],
                                'completed' => ['label' => 'Livré ✓', 'class' => 'bg-success-100 text-primary-600 border-success-200'],
                                'auto_completed' => ['label' => 'Livré ✓', 'class' => 'bg-success-100 text-primary-600 border-success-200'],
                                'disputed' => ['label' => 'Litige', 'class' => 'bg-danger-50 text-danger border-danger-200'],
                                'cancelled' => ['label' => 'Annulée', 'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
                                'failed' => ['label' => 'Échouée', 'class' => 'bg-danger-50 text-danger border-danger-200'],
                            ];

                            // Config spécifique pour le statut global du GROUPE
                            $groupStatusConfig = [
                                'pending' => ['label' => 'Groupe en cours', 'class' => 'bg-warning-100 text-warning-800 border-warning-200'],
                                'validated' => ['label' => 'Groupe validé ✓', 'class' => 'bg-success-100 text-primary-600 border-success-200'],
                                'completed' => ['label' => 'Groupe finalisé', 'class' => 'bg-primary-100 text-primary-800 border-primary-300'],
                                'cancelled' => ['label' => 'Groupe échoué / Annulé', 'class' => 'bg-danger-100 text-danger-700 border-danger-200'],
                            ];

                            $sc = $statusConfig[$order->status] ?? [
                                'label' => $order->status,
                                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
                            ];

                            $group = $order->orderGroup;
                            $gsc = $group ? ($groupStatusConfig[$group->status] ?? ['label' => $group->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200']) : null;
                        @endphp

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition-all overflow-hidden group">
                            
                            {{-- En-tête statut du groupe global --}}
                            @if ($group)
                                <div class="bg-success-50/50 border-b border-success-100 px-5 py-3">
                                    <div class="flex items-center justify-between text-xs mb-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center gap-1 bg-primary-600 text-white px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider">
                                                📦 Achat Groupé
                                            </span>
                                            <span class="font-bold text-slate-700">Réf Groupe : {{ $group->reference }}</span>
                                        </div>
                                        
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $gsc['class'] }}">
                                            {{ $gsc['label'] }}
                                        </span>
                                    </div>

                                    {{-- Lien vers l'ensemble des commandes du groupe --}}
                                    <div class="flex items-center justify-between pt-2 border-t border-success-100/60 text-xs">
                                        <span class="text-slate-500 text-[11px]">
                                            Fait partie d'un ensemble de commandes groupées
                                        </span>
                                        <a href="{{ route('buyer.orders.group.show', $group->id) }}"
                                           class="inline-flex items-center gap-1.5 font-extrabold text-primary-600 hover:text-primary-700 bg-white border border-success-200 hover:border-primary-600 px-3 py-1 rounded-lg transition-all shadow-2xs text-[11px]">
                                            <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                            <span>Voir toutes les commandes du groupe</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>

                                    {{-- Progression du groupe si en attente de participants --}}
                                    @if ($group->status === 'pending')
                                        @php
                                            $currentQty = $group->current_quantity ?? $group->orders()->sum('quantity') ?? 0;
                                            $targetQty = $group->target_quantity ?? 1;
                                            $percentage = min(100, round(($currentQty / $targetQty) * 100));
                                        @endphp
                                        <div class="mt-2.5 pt-2 border-t border-success-100/60">
                                            <div class="flex justify-between font-bold text-slate-600 mb-1 text-[11px]">
                                                <span>Objectif d'unités du groupe</span>
                                                <span>{{ $currentQty }} / {{ $targetQty }} ({{ $percentage }}%)</span>
                                            </div>
                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-primary-600 h-full rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('buyer.orders.show', $order) }}" class="block p-5">
                                {{-- Ligne supérieure : Réf & Statut individuel --}}
                                <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-black text-slate-900 text-sm tracking-tight group-hover:text-primary-600 transition-colors">
                                                {{ $order->reference }}
                                            </p>
                                        </div>
                                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                            Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold border uppercase tracking-wider {{ $sc['class'] }}">
                                        {{ $sc['label'] }}
                                    </span>
                                </div>

                                {{-- Ligne inférieure : Boutique & Total --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            <p class="text-xs font-bold text-slate-800">{{ $order->shop->name }}</p>
                                        </div>
                                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                            {{ $order->items->count() }} article(s)
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-primary-600 text-base sm:text-lg">
                                            {{ number_format($order->total_amount, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Alertes selon l'état de la commande --}}
                                @if ($order->status === 'awaiting_buyer_confirmation')
                                    <div class="mt-3 bg-accent-500/10 border border-accent-500/30 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-bold flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <span>📦</span> Votre colis est disponible en agence
                                        </span>
                                        <span class="text-primary-600 font-black underline">Récupérer avec OTP →</span>
                                    </div>
                                @endif

                                @if ($group && $group->status === 'pending')
                                    <div class="mt-3 bg-warning-50 border border-warning-200 rounded-xl px-3.5 py-2 text-xs text-warning-800 font-bold flex items-center gap-2">
                                        <span>⏳</span> En attente d'atteindre l'objectif du groupe pour valider la livraison.
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">{{ $orders->links() }}</div>
            @endif

        </div>
    </div>
@endsection