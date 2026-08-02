@extends('base')

@section('title', 'Conversation')

@section('content')
    <div class="bg-gray-50 min-h-screen flex flex-col">

        {{-- En-tête conversation --}}
        @php
            $user = auth()->user();
            $partner = $user->isBuyer() ? $conversation->shop->name : $conversation->buyer->name;
        @endphp

        <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-4">
                <a href="{{ route('messaging.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div
                    class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold
                        flex items-center justify-center uppercase text-sm">
                    {{ substr($partner, 0, 2) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $partner }}</p>
                    @if ($user->isBuyer())
                        {{-- Lien vers la boutique depuis la conversation --}}
                        <a href="{{ route('shop.show', $conversation->shop) }}"
                            class="text-xs text-indigo-500 hover:underline">
                            Voir la boutique →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Avertissement intégrité messages --}}
        <div class="max-w-3xl mx-auto px-4 py-2 w-full">
            <p class="text-xs text-center text-gray-400">
                Les messages sont horodatés et immuables — ils peuvent servir de preuve en cas de litige.
            </p>
        </div>

        {{-- Zone messages --}}
        <div class="flex-1 max-w-3xl mx-auto px-4 py-4 w-full space-y-3 overflow-y-auto" id="messagesContainer">

            @foreach ($conversation->messages as $message)
                @php
                    $isMine = $message->sender_id === $user->id;
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" data-id="{{ $message->id }}">
                    <div class="max-w-xs sm:max-w-md">

                        {{-- Nom de l'expéditeur (côté partenaire uniquement) --}}
                        @if (!$isMine)
                            <p class="text-xs text-gray-400 mb-1 ml-1">{{ $message->sender->name }}</p>
                        @endif

                        {{-- Bulle message --}}
                        <div
                            class="px-4 py-3 rounded-2xl text-sm
                                {{ $isMine
                                    ? 'bg-indigo-600 text-white rounded-br-sm'
                                    : 'bg-white border border-gray-200 text-gray-800 rounded-bl-sm shadow-sm' }}">

                            @if ($message->isText())
                                {{-- Message texte --}}
                                <p class="leading-relaxed whitespace-pre-wrap">{{ $message->body }}</p>
                            @elseif($message->isImage())
                                {{-- Image --}}
                                <img src="{{ asset('storage/' . $message->attachment_url) }}" alt="Image"
                                    class="rounded-xl max-w-full cursor-pointer" onclick="window.open(this.src)">
                            @elseif($message->isPdf())
                                {{-- Document PDF --}}
                                <a href="{{ asset('storage/' . $message->attachment_url) }}" target="_blank"
                                    class="flex items-center gap-2 {{ $isMine ? 'text-white' : 'text-indigo-600' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                    </svg>
                                    <span class="text-xs font-medium">
                                        Document PDF — {{ $message->fileSizeFormatted() }}
                                    </span>
                                </a>
                            @else
                                {{-- Bulle de message pour un produit / image avec texte --}}
                                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} mb-1">
                                    <div
                                        class="max-w-sm rounded-2xl overflow-hidden shadow-sm border border-slate-200 {{ $isMine ? 'bg-indigo-600 text-white' : 'bg-white text-slate-800' }}">

                                        {{-- Section 1: Image du produit --}}
                                        @if ($message->attachment_url)
                                            <div class="relative group overflow-hidden bg-slate-100">
                                                <img src="{{ asset('storage/' . $message->attachment_url) }}"
                                                    alt="Photo du produit"
                                                    class="w-full h-52 object-cover transition-transform duration-300 group-hover:scale-105 cursor-pointer"
                                                    onclick="window.open(this.src, '_blank')">

                                                {{-- Badges d'état (Pending, Processing, Success, Failed) --}}
                                                @if (isset($message->status))
                                                    <span
                                                        class="absolute top-2 right-2 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full backdrop-blur-md bg-black/40 text-white">
                                                        {{ $message->status }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Section 2: Contenu textuel et Métadonnées --}}
                                        <div class="p-1 space-y-1">

                                            {{-- Texte d'accroche ou description --}}
                                            @if ($message->body)
                                                <p class="text-sm font-medium leading-relaxed">
                                                    {{ $message->body }}
                                                </p>
                                            @endif

                                            {{-- Footer du message : Heure + Statut d'envoi --}}
                                            <div
                                                class="flex items-center justify-end gap-1.5 pt-1 text-[11px] {{ $isMine ? 'text-indigo-200' : 'text-slate-400' }}">
                                                @if ($isMine)
                                                    {{-- Icône de confirmation d'envoi --}}
                                                    <svg class="w-3.5 h-3.5 fill-current opacity-80" viewBox="0 0 20 20">
                                                        <path
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                                    </svg>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Horodatage --}}
                        <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }} px-1">
                            {{ $message->sent_at->format('d/m H:i') }}
                            @if ($isMine && $message->is_read)
                                · Lu
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach

            {{-- Ancre pour scroll automatique --}}
            <div id="bottomAnchor"></div>
        </div>

        {{-- Zone de saisie --}}
        <div class="bg-white border-t border-gray-200 sticky bottom-0">
            <div class="max-w-3xl mx-auto px-4 py-3">

                {{-- Envoi texte --}}
                <form method="POST" action="{{ route('messaging.send.text', $conversation) }}" class="flex gap-2 mb-2">
                    @csrf
                    <input type="text" name="body" id="messageInput" placeholder="Écrivez un message..."
                        maxlength="2000" autocomplete="off"
                        class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                               px-5 py-2.5 rounded-xl transition text-sm">
                        Envoyer
                    </button>
                </form>

                {{-- Envoi pièce jointe --}}
                <form method="POST" action="{{ route('messaging.send.attachment', $conversation) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <label
                        class="flex items-center gap-2 text-xs text-gray-400 cursor-pointer
                              hover:text-indigo-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span>Joindre une image (2 MB) ou un PDF (5 MB)</span>
                        {{-- Soumission automatique à la sélection du fichier --}}
                        <input type="file" name="attachment" class="hidden" accept="image/*,.pdf"
                            onchange="this.form.submit()">
                    </label>
                </form>

            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // Récupérer l'ID du dernier message pour le polling
            const messages = document.querySelectorAll('[data-id]');
            let lastMessageId = messages.length > 0 ?
                parseInt(messages[messages.length - 1].dataset.id) :
                0;

            const container = document.getElementById('messagesContainer');

            // Scroller automatiquement vers le bas
            function scrollToBottom() {
                document.getElementById('bottomAnchor').scrollIntoView({
                    behavior: 'smooth'
                });
            }
            scrollToBottom();

            // Polling toutes les 7 secondes pour les nouveaux messages
            setInterval(function() {
                fetch(`{{ route('messaging.poll', $conversation) }}?after_id=${lastMessageId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(newMessages => {
                        if (newMessages.length === 0) return;

                        // Ajouter les nouveaux messages dans le DOM
                        newMessages.forEach(msg => {
                            const isMine = msg.sender_id === {{ auth()->id() }};
                            lastMessageId = msg.id;

                            const div = document.createElement('div');
                            div.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
                            div.dataset.id = msg.id;

                            let content = '';
                            if (msg.type === 'text') {
                                content = `<p class="leading-relaxed whitespace-pre-wrap">${msg.body}</p>`;
                            } else {
                                content = `<p class="text-xs">📎 Pièce jointe</p>`;
                            }

                            div.innerHTML = `
                    <div class="max-w-xs sm:max-w-md">
                        <div class="px-4 py-3 rounded-2xl text-sm
                            ${isMine
                                ? 'bg-indigo-600 text-white rounded-br-sm'
                                : 'bg-white border border-gray-200 text-gray-800 rounded-bl-sm shadow-sm'}">
                            ${content}
                        </div>
                        <p class="text-xs text-gray-400 mt-1 ${isMine ? 'text-right' : 'text-left'} px-1">
                            À l'instant
                        </p>
                    </div>`;

                            // Insérer avant l'ancre de bas de page
                            container.insertBefore(div, document.getElementById('bottomAnchor'));
                        });

                        scrollToBottom();
                    })
                    .catch(() => {}); // Ignorer les erreurs réseau silencieusement
            }, 7000);
        </script>
    @endpush
@endsection
