@extends('base')
@section('title', 'Litiges')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Litiges reçus</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($disputes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="text-4xl mb-3">✅</div>
            <p class="text-gray-500 text-sm">Aucun litige. Continuez comme ça !</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($disputes as $dispute)
                @php
                    $statusConfig = [
                        'open'           => ['label' => 'À répondre',        'class' => 'bg-red-100 text-red-700'],
                        'seller_replied' => ['label' => 'Réponse envoyée',   'class' => 'bg-blue-100 text-blue-700'],
                        'under_review'   => ['label' => 'En cours d\'examen','class' => 'bg-purple-100 text-purple-700'],
                        'resolved'       => ['label' => 'Résolu',            'class' => 'bg-emerald-100 text-emerald-700'],
                        'closed'         => ['label' => 'Clôturé',           'class' => 'bg-gray-100 text-gray-500'],
                    ];
                    $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-gray-100'];
                @endphp

                <a href="{{ route('seller.disputes.show', $dispute) }}"
                   class="block bg-white rounded-2xl border
                          {{ $dispute->status === 'open' ? 'border-red-200' : 'border-gray-100' }}
                          shadow-sm hover:shadow-md transition p-5">

                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-bold text-gray-900">{{ $dispute->order->reference }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
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
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                            @if($dispute->order->items->first()->product?->images?->first())
                                <img src="{{ asset('storage/' . $dispute->order->items->first()->product->images->first()->url) }}"
                                     class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    {{ $dispute->order->items->first()->product_title }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $dispute->typeLabel() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Alerte délai --}}
                    @if($dispute->status === 'open')
                        <div class="text-xs {{ $dispute->sellerReplyExpired() ? 'text-red-500 font-bold' : 'text-orange-500' }}">
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