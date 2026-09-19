@extends('layouts.seller')
@section('title', 'Litiges')
@section('content')
<div class="bg-slate-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Litiges reçus</h1>

    @if(session('success'))
        <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($disputes->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
            <div class="text-4xl mb-3">✅</div>
            <p class="text-slate-500 text-sm">Aucun litige. Continuez comme ça !</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($disputes as $dispute)
                @php
                    $statusConfig = [
                        'open'           => ['label' => 'À répondre',        'class' => 'bg-danger-100 text-danger-700'],
                        'seller_replied' => ['label' => 'Réponse envoyée',   'class' => 'bg-primary-100 text-primary-700'],
                        'under_review'   => ['label' => 'En cours d\'examen','class' => 'bg-primary-100 text-primary-700'],
                        'resolved'       => ['label' => 'Résolu',            'class' => 'bg-success-100 text-success-700'],
                        'closed'         => ['label' => 'Clôturé',           'class' => 'bg-slate-100 text-slate-500'],
                    ];
                    $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-slate-100'];
                @endphp

                <a href="{{ route('seller.disputes.show', $dispute) }}"
                   class="block bg-white rounded-2xl border
                          {{ $dispute->status === 'open' ? 'border-danger-200' : 'border-slate-100' }}
                          shadow-sm hover:shadow-md transition p-5">

                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-bold text-slate-900">{{ $dispute->order->reference }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Acheteur : {{ $dispute->order->buyer->name }}
                                — {{ $dispute->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>

                    {{-- Article --}}
                    @if($dispute->order->items->first())
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl mb-3">
                            @if($dispute->order->items->first()->product?->images?->first())
                                <img src="{{ asset('storage/' . $dispute->order->items->first()->product->images->first()->url) }}"
                                     class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">
                                    {{ $dispute->order->items->first()->product_title }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $dispute->typeLabel() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Alerte délai --}}
                    @if($dispute->status === 'open')
                        <div class="text-xs {{ $dispute->sellerReplyExpired() ? 'text-danger font-bold' : 'text-accent-500' }}">
                            ⏱ Délai de réponse :
                            {{ $dispute->seller_reply_deadline?->format('d/m/Y à H:i') }}
                            @if($dispute->sellerReplyExpired()) — EXPIRÉ @endif
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