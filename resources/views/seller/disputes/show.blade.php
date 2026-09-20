@extends('layouts.seller')
@section('title', 'Litige — ' . $dispute->order->reference)
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Litige</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Commande {{ $dispute->order->reference }}
            </p>
        </div>
        @php
            $statusConfig = [
                'open'           => ['label' => 'À répondre',        'class' => 'bg-red-100 text-red-700'],
                'seller_replied' => ['label' => 'Réponse envoyée',   'class' => 'bg-blue-100 text-blue-700'],
                'under_review'   => ['label' => 'En cours d\'examen','class' => 'bg-purple-100 text-purple-700'],
                'resolved'       => ['label' => 'Résolu',            'class' => 'bg-emerald-100 text-emerald-700'],
            ];
            $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-gray-100'];
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

    <div class="space-y-5">

        {{-- Décision si résolu --}}
        @if($dispute->isResolved())
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
                <h2 class="font-bold text-emerald-800 mb-2">Décision de l'administrateur</h2>
                <p class="text-sm font-semibold text-emerald-700">{{ $dispute->resolutionLabel() }}</p>
                @if($dispute->resolution_amount)
                    <p class="text-sm text-emerald-600 mt-1">
                        Montant : {{ number_format($dispute->resolution_amount, 0, ',', ' ') }} FCFA
                    </p>
                @endif
                <p class="text-sm text-emerald-700 mt-2">{{ $dispute->resolution_note }}</p>
            </div>
        @endif

        {{-- Acheteur --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-3">Acheteur</h2>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nom</span>
                    <span class="font-medium">{{ $dispute->order->buyer->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Téléphone</span>
                    <span>{{ $dispute->order->buyer->phone }}</span>
                </div>
            </div>
        </div>

        {{-- Article concerné --}}
        @if($dispute->order->items->first())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-3">Article concerné</h2>
                <div class="flex gap-4">
                    @if($dispute->order->items->first()->product?->images?->first())
                        <img src="{{ asset('storage/' . $dispute->order->items->first()->product->images->first()->url) }}"
                             class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                    @endif
                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $dispute->order->items->first()->purchasedLabel() }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ number_format($dispute->order->subtotal, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Motif et description --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="font-bold text-gray-800 mb-2">
                {{ $dispute->typeLabel() }}
            </h2>
            @if($dispute->seller_reply_deadline && $dispute->isOpen())
                <p class="text-xs {{ $dispute->sellerReplyExpired() ? 'text-red-500 font-bold' : 'text-orange-500' }} mb-3">
                    ⏱ Délai de réponse :
                    {{ $dispute->seller_reply_deadline->format('d/m/Y à H:i') }}
                    @if($dispute->sellerReplyExpired()) — EXPIRÉ @endif
                </p>
            @endif
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-800 leading-relaxed">{{ $dispute->description }}</p>
            </div>
        </div>

        {{-- Preuves --}}
        @if($dispute->evidences->count())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-4">
                    Preuves ({{ $dispute->evidences->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($dispute->evidences as $evidence)
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-500 mb-2">
                                {{ $evidence->submitter->name }}
                                — {{ $evidence->created_at->format('d/m H:i') }}
                            </p>
                            @if($evidence->isText())
                                <p class="text-sm text-gray-800 italic">
                                    "{{ $evidence->content }}"
                                </p>
                            @elseif($evidence->isPhoto())
                                <img src="{{ asset('storage/' . $evidence->url) }}"
                                     class="rounded-xl max-w-sm w-full cursor-pointer"
                                     onclick="window.open(this.src)">
                            @else
                                <a href="{{ asset('storage/' . $evidence->url) }}"
                                   target="_blank"
                                   class="text-indigo-600 hover:underline text-sm">
                                    📄 {{ $evidence->description ?? 'Document' }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Formulaire réponse vendeur --}}
        @if($dispute->isOpen())
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-1">Votre réponse</h2>
                <p class="text-xs text-red-500 mb-4">
                    ⚠ Répondez dans les délais. Une non-réponse peut être interprétée en votre défaveur.
                </p>
                <form method="POST"
                      action="{{ route('seller.disputes.reply', $dispute) }}"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm">
                            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                        </div>
                    @endif
                    <textarea name="response" rows="5" required
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm
                                     focus:ring-2 focus:ring-indigo-500"
                              placeholder="Expliquez clairement votre position. Joignez des preuves si possible..."></textarea>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Photos ou documents
                        </label>
                        <input type="file" name="files[]"
                               accept="image/*,.pdf" multiple
                               class="w-full border border-gray-300 rounded-xl p-2 text-sm">
                    </div>
                    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white
                                   font-bold py-3 rounded-xl transition text-sm">
                        Envoyer ma réponse
                    </button>
                </form>
            </div>
        @elseif($dispute->status === 'seller_replied')
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-700">
                ✅ Vous avez répondu. L'administrateur va examiner le dossier.
            </div>
        @endif

        <a href="{{ route('seller.disputes.index') }}"
           class="block text-sm text-indigo-600 hover:underline">← Mes litiges</a>

    </div>
</div>
</div>
@endsection