@extends('base')
@section('title', 'Litige — ' . $dispute->order->reference)
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Litige</h1>
            <p class="text-sm text-gray-500 mt-0.5">Commande {{ $dispute->order->reference }}</p>
        </div>
        @php
            $statusConfig = [
                'open'           => ['label' => 'Ouvert',              'class' => 'bg-yellow-100 text-yellow-700'],
                'seller_replied' => ['label' => 'Vendeur a répondu',   'class' => 'bg-blue-100 text-blue-700'],
                'under_review'   => ['label' => 'En cours d\'examen',  'class' => 'bg-purple-100 text-purple-700'],
                'resolved'       => ['label' => 'Résolu',              'class' => 'bg-emerald-100 text-emerald-700'],
            ];
            $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-gray-100 text-gray-600'];
        @endphp
        <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $sc['class'] }}">
            {{ $sc['label'] }}
        </span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Décision si résolu --}}
    @if($dispute->isResolved())
        <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-5 mb-6">
            <h2 class="font-bold text-gray-800 mb-3">✅ Décision de l'administrateur</h2>
            <p class="text-sm font-semibold text-indigo-600 mb-2">
                {{ $dispute->resolutionLabel() }}
            </p>
            @if($dispute->resolution_amount)
                <p class="text-sm text-gray-600">
                    Montant : {{ number_format($dispute->resolution_amount, 0, ',', ' ') }} FCFA
                </p>
            @endif
            <p class="text-sm text-gray-600 mt-2">{{ $dispute->resolution_note }}</p>
        </div>
    @endif

    <div class="space-y-5">

        {{-- Détails du litige --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-3">Détails</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Motif</span>
                    <span class="font-medium">{{ $dispute->typeLabel() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ouvert par</span>
                    <span class="font-medium">{{ $dispute->initiator->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date d'ouverture</span>
                    <span>{{ $dispute->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($dispute->seller_reply_deadline && $dispute->isOpen())
                    <div class="flex justify-between">
                        <span class="text-gray-500">Délai réponse vendeur</span>
                        <span class="{{ $dispute->sellerReplyExpired() ? 'text-red-500 font-bold' : '' }}">
                            {{ $dispute->seller_reply_deadline->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50">
                <p class="text-xs text-gray-500 mb-1">Description</p>
                <p class="text-sm text-gray-800">{{ $dispute->description }}</p>
            </div>
        </div>

        {{-- Preuves --}}
        @if($dispute->evidences->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-4">Preuves soumises</h2>
                <div class="space-y-3">
                    @foreach($dispute->evidences as $evidence)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 mb-1">
                                    {{ $evidence->submitter->name }}
                                    — {{ $evidence->created_at->format('d/m H:i') }}
                                </p>
                                @if($evidence->isText())
                                    <p class="text-sm text-gray-800">{{ $evidence->content }}</p>
                                @elseif($evidence->isPhoto())
                                    <img src="{{ asset('storage/' . $evidence->url) }}"
                                         class="rounded-lg max-w-xs cursor-pointer"
                                         onclick="window.open(this.src)">
                                @else
                                    <a href="{{ asset('storage/' . $evidence->url) }}"
                                       target="_blank" class="text-sm text-indigo-600 hover:underline">
                                        📄 {{ $evidence->description }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Répondre (vendeur) --}}
        @if(auth()->user()->isSeller() && $dispute->isOpen())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-4">Votre réponse</h2>
                <form method="POST"
                      action="{{ route('seller.disputes.reply', $dispute) }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <textarea name="response" rows="4" required
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                                     focus:ring-2 focus:ring-indigo-500"
                              placeholder="Expliquez votre position et fournissez des preuves si nécessaire..."></textarea>
                    <input type="file" name="files[]" accept="image/*,.pdf" multiple
                           class="w-full border border-gray-300 rounded-xl p-2 text-sm">
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white
                                   font-bold py-3 rounded-xl transition text-sm">
                        Envoyer ma réponse
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
</div>
@endsection