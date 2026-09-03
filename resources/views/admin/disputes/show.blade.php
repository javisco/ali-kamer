@extends('layouts.admin')
@section('title', 'Dossier litige')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">
                    Dossier litige — {{ $dispute->order->reference }}
                </h1>
                <a href="{{ route('admin.disputes.index') }}" class="text-sm text-indigo-600 hover:underline">← Retour</a>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Colonne principale --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Infos commande --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-3">Commande</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Référence</span>
                                <span class="font-medium">{{ $dispute->order->reference }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Acheteur</span>
                                <span>{{ $dispute->order->buyer->name }} — {{ $dispute->order->buyer->phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Vendeur</span>
                                <span>{{ $dispute->order->shop->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Montant total</span>
                                <span class="font-bold">{{ number_format($dispute->order->total_amount, 0, ',', ' ') }}
                                    FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Montant net vendeur</span>
                                <span class="font-bold">{{ number_format($dispute->order->net_amount, 0, ',', ' ') }}
                                    FCFA</span>
                            </div>
                        </div>
                    </div>

                    {{-- Motif et description --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-3">
                            Litige — {{ $dispute->typeLabel() }}
                        </h2>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $dispute->description }}</p>
                    </div>

                    {{-- Preuves --}}
                    @if ($dispute->evidences->count())
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <h2 class="font-bold text-gray-800 mb-4">
                                Preuves ({{ $dispute->evidences->count() }})
                            </h2>
                            <div class="space-y-4">
                                @foreach ($dispute->evidences as $evidence)
                                    <div class="p-4 bg-gray-50 rounded-xl">
                                        <p class="text-xs font-semibold text-gray-500 mb-2">
                                            {{ $evidence->submitter->name }}
                                            — {{ $evidence->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        @if ($evidence->isText())
                                            <p class="text-sm text-gray-800 italic">
                                                "{{ $evidence->content }}"
                                            </p>
                                        @elseif($evidence->isPhoto())
                                            <img src="{{ asset('storage/' . $evidence->url) }}"
                                                class="rounded-xl max-w-sm cursor-pointer" onclick="window.open(this.src)">
                                        @else
                                            <a href="{{ asset('storage/' . $evidence->url) }}" target="_blank"
                                                class="text-indigo-600 hover:underline text-sm">
                                                📄 {{ $evidence->description }}
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
                {{-- Conversation entre acheteur et vendeur --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mt-5">
                    <h2 class="font-bold text-gray-800 mb-1">
                        💬 Historique de la conversation
                    </h2>
                    <p class="text-xs text-gray-400 mb-4">
                        Messages échangés entre {{ $dispute->order->buyer->name }}
                        et {{ $dispute->order->shop->name }} avant le litige.
                    </p>

                    @if ($conversation && $conversation->messages->count())
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                            @foreach ($conversation->messages as $message)
                                @php
                                    $isBuyer = $message->sender_id === $dispute->order->buyer_id;
                                @endphp
                                <div class="flex {{ $isBuyer ? 'justify-start' : 'justify-end' }}">
                                    <div class="max-w-sm">
                                        <p class="text-xs text-gray-400 mb-1 {{ $isBuyer ? 'text-left' : 'text-right' }}">
                                            {{ $message->sender->name }}
                                            — {{ $message->sent_at->format('d/m H:i') }}
                                        </p>
                                        <div
                                            class="px-4 py-2.5 rounded-2xl text-sm
                            {{ $isBuyer ? 'bg-gray-100 text-gray-800 rounded-bl-sm' : 'bg-indigo-600 text-white rounded-br-sm' }}">
                                            @if ($message->isText())
                                                {{ $message->body }}
                                            @elseif($message->isImage())
                                                <img src="{{ asset('storage/' . $message->attachment_url) }}"
                                                    class="rounded-lg max-w-xs cursor-pointer"
                                                    onclick="window.open(this.src)">
                                            @else
                                                <a href="{{ asset('storage/' . $message->attachment_url) }}"
                                                    target="_blank"
                                                    class="{{ $isBuyer ? 'text-indigo-600' : 'text-white' }}
                                          hover:underline text-xs">
                                                    📄 {{ $message->attachment_url }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-400 text-center">
                            Aucune conversation entre ces deux parties pour cette boutique.
                        </div>
                    @endif
                </div>
                {{-- Colonne décision --}}
                <div class="space-y-5">

                    @if (!$dispute->isResolved())
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <h2 class="font-bold text-gray-800 mb-4">Décision</h2>

                            <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}"
                                class="space-y-4">
                                @csrf

                                {{-- Résolution --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Décision <span class="text-red-500">*</span>
                                    </label>
                                    <select name="resolution" required
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                           focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Choisir --</option>
                                        @foreach (App\Models\Dispute::RESOLUTIONS as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Montant partiel --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Montant remboursement partiel (FCFA)
                                    </label>
                                    <input type="number" name="resolution_amount" min="0"
                                        placeholder="Laisser vide si non applicable"
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                          focus:ring-2 focus:ring-indigo-500">
                                </div>

                                {{-- Note --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                        Note de décision <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="resolution_note" rows="4" required
                                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                                             focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Justifiez votre décision..."></textarea>
                                </div>

                                <button
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white
                                       font-bold py-3 rounded-xl transition text-sm">
                                    Appliquer la décision
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
                            <h2 class="font-bold text-emerald-800 mb-2">Décision appliquée</h2>
                            <p class="text-sm font-semibold text-emerald-700">
                                {{ $dispute->resolutionLabel() }}
                            </p>
                            @if ($dispute->resolution_amount)
                                <p class="text-sm text-emerald-600 mt-1">
                                    {{ number_format($dispute->resolution_amount, 0, ',', ' ') }} FCFA
                                </p>
                            @endif
                            <p class="text-sm text-emerald-700 mt-2">{{ $dispute->resolution_note }}</p>
                            <p class="text-xs text-emerald-500 mt-2">
                                Par {{ $dispute->resolver->name }}
                                — {{ $dispute->resolved_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
