@extends('layouts.buyer')
@section('title', 'Mes litiges')
@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Mes litiges</h1>

            @if (session('success'))
                <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($disputes->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
                    <div class="text-4xl mb-3">✅</div>
                    <p class="text-slate-500 text-sm">Aucun litige. Tout va bien !</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($disputes as $dispute)
                        @php
                            $statusConfig = [
                                'open' => ['label' => 'En attente vendeur', 'class' => 'bg-warning-100 text-warning-700'],
                                'seller_replied' => [
                                    'label' => 'Vendeur a répondu',
                                    'class' => 'bg-primary-100 text-primary-700',
                                ],
                                'under_review' => [
                                    'label' => 'En cours d\'examen',
                                    'class' => 'bg-primary-100 text-primary-700',
                                ],
                                'resolved' => ['label' => 'Résolu', 'class' => 'bg-success-100 text-success-700'],
                                'closed' => ['label' => 'Clôturé', 'class' => 'bg-slate-100 text-slate-500'],
                            ];
                            $sc = $statusConfig[$dispute->status] ?? [
                                'label' => $dispute->status,
                                'class' => 'bg-slate-100 text-slate-500',
                            ];
                        @endphp

                        <a href="{{ route('buyer.disputes.show', $dispute) }}"
                            class="block bg-white rounded-2xl border border-slate-100 shadow-sm
                          hover:shadow-md hover:border-primary-200 transition p-5">

                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $dispute->order->reference }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $dispute->order->shop->name }}
                                        — Ouvert le {{ $dispute->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                                    {{ $sc['label'] }}
                                </span>
                            </div>

                            {{-- Article concerné --}}
                            @if ($dispute->order->items->first())
                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl mb-3">
                                    @if ($dispute->order->items->first()->product?->images?->first())
                                        <img src="{{ asset('storage/' . $dispute->order->items->first()->product->images->first()->url) }}"
                                            class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-800 truncate">
                                            {{ $dispute->order->items->first()->product_title }}
                                        </p>
                                        <p class="text-xs text-slate-400">
                                            Motif : {{ $dispute->typeLabel() }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- Décision si résolu --}}
                            @if ($dispute->isResolved())
                                <div class="bg-success-50 rounded-xl px-3 py-2 text-xs text-success-700">
                                    ✅ Décision : {{ $dispute->resolutionLabel() }}
                                </div>
                            @elseif($dispute->status === 'open' && $dispute->seller_reply_deadline)
                                <div class="text-xs text-accent-500">
                                    ⏱ Délai réponse vendeur :
                                    {{ $dispute->seller_reply_deadline->format('d/m/Y à H:i') }}
                                    @if ($dispute->sellerReplyExpired())
                                        <span class="font-bold">(Expiré)</span>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="mt-4">{{ $disputes->links() }}</div>
            @endif

        </div>
    </div>
@endsection
