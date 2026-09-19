@extends('layouts.buyer')
@section('title', 'Litige — ' . $dispute->order->reference)
@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Litige</h1>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Commande {{ $dispute->order->reference }}
                        — {{ $dispute->order->shop->name }}
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'open' => ['label' => 'Ouvert', 'class' => 'bg-warning-100 text-warning-700'],
                        'seller_replied' => ['label' => 'Vendeur a répondu', 'class' => 'bg-primary-100 text-primary-700'],
                        'under_review' => ['label' => 'En cours d\'examen', 'class' => 'bg-primary-100 text-primary-700'],
                        'resolved' => ['label' => 'Résolu', 'class' => 'bg-success-100 text-success-700'],
                        'closed' => ['label' => 'Clôturé', 'class' => 'bg-slate-100 text-slate-500'],
                    ];
                    $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-slate-100'];
                @endphp
                <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $sc['class'] }}">
                    {{ $sc['label'] }}
                </span>
            </div>

            @if (session('success'))
                <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-5">

                {{-- Décision admin si résolu --}}
                @if ($dispute->isResolved())
                    <div class="bg-success-50 border border-success-200 rounded-2xl p-5">
                        <h2 class="font-bold text-success-800 mb-3">✅ Décision de l'administrateur</h2>
                        <p class="text-sm font-semibold text-success-700 mb-2">
                            {{ $dispute->resolutionLabel() }}
                        </p>
                        @if ($dispute->resolution_amount)
                            <p class="text-sm text-success">
                                Montant : {{ number_format($dispute->resolution_amount, 0, ',', ' ') }} FCFA
                            </p>
                        @endif
                        <p class="text-sm text-success-700 mt-2">{{ $dispute->resolution_note }}</p>
                        <p class="text-xs text-success mt-2">
                            Décidé le {{ $dispute->resolved_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                @endif

                {{-- Article concerné --}}
                @if ($dispute->order->items->first())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                        <h2 class="font-bold text-slate-800 mb-3">Article concerné</h2>
                        <div class="flex gap-4">
                            @if ($dispute->order->items->first()->product?->images?->first())
                                <img src="{{ asset('storage/' . $dispute->order->items->first()->product->images->first()->url) }}"
                                    class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                            @endif
                            <div>
                                <p class="font-medium text-slate-900">
                                    {{ $dispute->order->items->first()->purchasedLabel() }}
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    Qté : {{ $dispute->order->items->first()->quantity }}
                                    — {{ number_format($dispute->order->items->first()->subtotal, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Détails du litige --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <h2 class="font-bold text-slate-800 mb-3">Détails du litige</h2>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Motif</span>
                            <span class="font-medium">{{ $dispute->typeLabel() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Ouvert par</span>
                            <span class="font-medium">{{ $dispute->initiator->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Date d'ouverture</span>
                            <span>{{ $dispute->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        @if ($dispute->seller_reply_deadline && $dispute->isOpen())
                            <div class="flex justify-between">
                                <span class="text-slate-500">Délai réponse vendeur</span>
                                <span
                                    class="{{ $dispute->sellerReplyExpired() ? 'text-danger font-bold' : 'text-accent-500' }}">
                                    {{ $dispute->seller_reply_deadline->format('d/m/Y à H:i') }}
                                    @if ($dispute->sellerReplyExpired())
                                        (Expiré)
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-500 mb-1">Description</p>
                        <p class="text-sm text-slate-800 leading-relaxed">{{ $dispute->description }}</p>
                    </div>
                </div>

                {{-- Preuves --}}
                @if ($dispute->evidences->count())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                        <h2 class="font-bold text-slate-800 mb-4">
                            Preuves ({{ $dispute->evidences->count() }})
                        </h2>
                        <div class="space-y-4">
                            @foreach ($dispute->evidences as $evidence)
                                <div class="border border-slate-100 rounded-xl p-4">
                                    <p class="text-xs font-semibold text-slate-500 mb-2">
                                        {{ $evidence->submitter->name }}
                                        — {{ $evidence->created_at->format('d/m H:i') }}
                                    </p>
                                    @if ($evidence->isText())
                                        <p class="text-sm text-slate-800 italic">
                                            "{{ $evidence->content }}"
                                        </p>
                                    @elseif($evidence->isPhoto())
                                        <img src="{{ asset('storage/' . $evidence->url) }}"
                                            alt="{{ $evidence->description }}"
                                            class="rounded-xl max-w-sm w-full cursor-pointer"
                                            onclick="window.open(this.src)">
                                        @if ($evidence->description)
                                            <p class="text-xs text-slate-400 mt-1">{{ $evidence->description }}</p>
                                        @endif
                                    @else
                                        <a href="{{ asset('storage/' . $evidence->url) }}" target="_blank"
                                            class="flex items-center gap-2 text-primary-600 hover:underline text-sm">
                                            <span>📄</span>
                                            <span>{{ $evidence->description ?? 'Document' }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Répondre (vendeur uniquement) --}}
                @if (auth()->user()->isSeller() && $dispute->isOpen())
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                        <h2 class="font-bold text-slate-800 mb-4">Votre réponse</h2>
                        <form method="POST" action="{{ route('seller.disputes.reply', $dispute) }}"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @if ($errors->any())
                                <div class="bg-danger-50 border border-danger-200 text-danger-700 px-3 py-2 rounded-lg text-sm">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <textarea name="response" rows="5" required
                                class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                                     focus:ring-2 focus:ring-primary-500"
                                placeholder="Expliquez votre position avec précision (minimum 20 caractères)..."></textarea>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Photos ou documents (optionnel)
                                </label>
                                <input type="file" name="files[]" accept="image/*,.pdf" multiple
                                    class="w-full border border-slate-300 rounded-xl p-2 text-sm">
                            </div>
                            <button
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white
                                   font-bold py-3 rounded-xl transition text-sm">
                                Envoyer ma réponse
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Lien retour --}}
                <div>
                    @if (auth()->user()->isBuyer())
                        <a href="{{ route('buyer.disputes.index') }}" class="text-sm text-primary-600 hover:underline">← Mes
                            litiges</a>
                    @else
                        <a href="{{ route('seller.disputes.index') }}" class="text-sm text-primary-600 hover:underline">←
                            Mes litiges</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
