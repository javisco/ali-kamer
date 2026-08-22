@extends('base')

@section('title', 'Conversation')

@section('content')
    <div class="bg-gray-100 min-h-screen flex flex-col font-sans">

        {{-- En-tête conversation --}}
        @php
            $user = auth()->user();
            $partner = $user->isBuyer() ? $conversation->shop->name : $conversation->buyer->name;
        @endphp

        <div class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
            <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('messaging.index') }}" class="text-gray-400 hover:text-gray-600 transition p-1 -ml-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div
                        class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center uppercase text-xs">
                        {{ substr($partner, 0, 2) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $partner }}</p>
                        @if ($user->isBuyer())
                            <a href="{{ route('shop.show', $conversation->shop) }}"
                                class="text-[11px] text-indigo-600 hover:underline">
                                Voir la boutique →
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Note sécurité discrète --}}
                <span
                    class="text-[10px] text-gray-400 hidden sm:inline-block bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                    🔒 Messages immuables & horodatés
                </span>
            </div>
        </div>

        {{-- Zone messages --}}
        <div class="flex-1 max-w-3xl mx-auto px-4 py-4 w-full space-y-3 overflow-y-auto" id="messagesContainer">
            {{-- En-tête conversation — ajouter score acheteur pour le vendeur --}}
            @if (auth()->user()->isSeller())
                @php
                    $buyer = $conversation->buyer;
                @endphp
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="text-xs {{ $buyer->trust_score >= 70
                            ? 'text-emerald-500'
                            : ($buyer->trust_score >= 40
                                ? 'text-orange-400'
                                : 'text-red-500') }}">
                        Score : {{ $buyer->trust_score }}/100
                    </span>
                    @if ($buyer->prepayment_required)
                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full">
                            ⚠ Prépaiement requis
                        </span>
                    @endif
                </div>
            @endif
            @foreach ($conversation->messages as $message)
                @php
                    $isMine = $message->sender_id === $user->id;
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}" data-id="{{ $message->id }}">
                    <div class="max-w-[85%] sm:max-w-md space-y-1">

                        {{-- Nom expéditeur (partenaire uniquement) --}}
                        @if (!$isMine)
                            <p class="text-[11px] font-semibold text-gray-500 ml-1">{{ $message->sender->name }}</p>
                        @endif
                        @if (auth()->user()->isSeller())
                            @php
                                $buyer = $conversation->buyer;
                            @endphp
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-xs {{ $buyer->trust_score >= 70
                                        ? 'text-emerald-500'
                                        : ($buyer->trust_score >= 40
                                            ? 'text-orange-400'
                                            : 'text-red-500') }}">
                                    Score : {{ $buyer->trust_score }}/100
                                </span>
                                @if ($buyer->prepayment_required)
                                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full">
                                        ⚠ Prépaiement requis
                                    </span>
                                @endif
                            </div>
                        @endif
                        {{-- Bulle du message --}}
                        <div
                            class="px-3.5 py-2.5 rounded-2xl text-sm shadow-sm relative {{ $isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-none' }}">

                            @if ($message->isText())
                                <p class="leading-relaxed whitespace-pre-wrap text-xs sm:text-sm">{{ $message->body }}</p>
                            @elseif($message->isImage())
                                <div class="relative group overflow-hidden rounded-xl">
                                    <img src="{{ asset('storage/' . $message->attachment_url) }}" alt="Image"
                                        class="rounded-xl max-w-full max-h-60 object-cover cursor-pointer hover:opacity-95 transition"
                                        onclick="window.open(this.src, '_blank')">

                                    @if (isset($message->status))
                                        {{-- Attribute names in English: pending, processing, success, failed --}}
                                        <span
                                            class="absolute top-2 right-2 px-2 py-0.5 text-[9px] font-bold uppercase rounded-full bg-black/50 backdrop-blur-sm text-white">
                                            {{ $message->status }}
                                        </span>
                                    @endif
                                </div>
                                @if ($message->body)
                                    <p class="mt-2 text-xs sm:text-sm leading-relaxed">{{ $message->body }}</p>
                                @endif
                            @elseif($message->isPdf())
                                <a href="{{ asset('storage/' . $message->attachment_url) }}" target="_blank"
                                    class="flex items-center gap-2.5 py-1 {{ $isMine ? 'text-white' : 'text-indigo-600' }}">
                                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                    </svg>
                                    <span class="text-xs font-medium underline truncate">
                                        Document PDF ({{ $message->fileSizeFormatted() }})
                                    </span>
                                </a>
                                @if ($message->body)
                                    <p class="mt-1 text-xs sm:text-sm leading-relaxed">{{ $message->body }}</p>
                                @endif
                            @else
                                {{-- Message générique / fallback --}}
                                @if ($message->attachment_url)
                                    <img src="{{ asset('storage/' . $message->attachment_url) }}" alt="Pièce jointe"
                                        class="rounded-xl max-w-full max-h-60 object-cover mb-2 cursor-pointer"
                                        onclick="window.open(this.src, '_blank')">
                                @endif
                                @if ($message->body)
                                    <p class="text-xs sm:text-sm leading-relaxed">{{ $message->body }}</p>
                                @endif
                            @endif

                            {{-- Horodatage et coche intégrés à la bulle --}}
                            <div
                                class="flex items-center justify-end gap-1 mt-1 text-[10px] {{ $isMine ? 'text-indigo-200' : 'text-gray-400' }}">
                                <span>{{ $message->sent_at->format('H:i') }}</span>
                                @if ($isMine)
                                    @if ($message->is_read)
                                        <span class="font-bold text-white">✓✓</span>
                                    @else
                                        <span>✓</span>
                                    @endif
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach

            <div id="bottomAnchor"></div>
        </div>

        {{-- Zone de saisie ultra-compacte --}}
        <div class="bg-white border-t border-gray-200 sticky bottom-0 z-10">
            <div class="max-w-3xl mx-auto px-4 py-2.5">

                <form method="POST" action="{{ route('messaging.send.text', $conversation) }}"
                    class="flex items-center gap-2" id="chatForm">
                    @csrf

                    {{-- Bouton pièce jointe unifié --}}
                    <label
                        class="text-gray-400 hover:text-indigo-600 transition cursor-pointer p-1.5 rounded-lg hover:bg-gray-100 flex-shrink-0"
                        title="Joindre une image ou un PDF">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <input type="file" name="attachment" class="hidden" accept="image/*,.pdf" id="attachmentInput"
                            onchange="submitAttachmentForm()">
                    </label>

                    {{-- Champ texte principal --}}
                    <input type="text" name="body" id="messageInput" placeholder="Écrivez un message..."
                        maxlength="2000" autocomplete="off"
                        class="flex-1 border border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                    {{-- Bouton envoi --}}
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-xl transition text-sm flex items-center justify-center flex-shrink-0">
                        <span>Envoyer</span>
                    </button>
                </form>

                {{-- Formulaire invisible pour envoi de pièce jointe sans rechargement lourd --}}
                <form id="attachmentForm" method="POST" action="{{ route('messaging.send.attachment', $conversation) }}"
                    enctype="multipart/form-data" class="hidden">
                    @csrf
                    <div id="attachmentContainer"></div>
                </form>

            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // Transfert de l'input file vers le formulaire d'envoi de fichier
            function submitAttachmentForm() {
                const fileInput = document.getElementById('attachmentInput');
                const attachmentForm = document.getElementById('attachmentForm');
                const container = document.getElementById('attachmentContainer');

                if (fileInput.files.length > 0) {
                    container.appendChild(fileInput.cloneNode(true));
                    attachmentForm.submit();
                }
            }

            // Récupérer l'ID du dernier message
            const messages = document.querySelectorAll('[data-id]');
            let lastMessageId = messages.length > 0 ? parseInt(messages[messages.length - 1].dataset.id) : 0;

            const container = document.getElementById('messagesContainer');

            function scrollToBottom() {
                document.getElementById('bottomAnchor').scrollIntoView({
                    behavior: 'smooth'
                });
            }
            scrollToBottom();

            // Polling automatique pour les nouveaux messages (7 sec)
            setInterval(function() {
                fetch(`{{ route('messaging.poll', $conversation) }}?after_id=${lastMessageId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(newMessages => {
                        if (newMessages.length === 0) return;

                        newMessages.forEach(msg => {
                            const isMine = msg.sender_id === {{ auth()->id() }};
                            lastMessageId = msg.id;

                            const div = document.createElement('div');
                            div.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
                            div.dataset.id = msg.id;

                            let content = msg.type === 'text' ?
                                `<p class="leading-relaxed whitespace-pre-wrap text-xs sm:text-sm">${msg.body}</p>` :
                                `<p class="text-xs font-medium">📎 Pièce jointe reçue</p>`;

                            div.innerHTML = `
                                <div class="max-w-[85%] sm:max-w-md space-y-1">
                                    <div class="px-3.5 py-2.5 rounded-2xl text-sm shadow-sm relative ${isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-none'}">
                                        ${content}
                                        <div class="flex items-center justify-end gap-1 mt-1 text-[10px] ${isMine ? 'text-indigo-200' : 'text-gray-400'}">
                                            <span>À l'instant</span>
                                        </div>
                                    </div>
                                </div>`;

                            container.insertBefore(div, document.getElementById('bottomAnchor'));
                        });

                        scrollToBottom();
                    })
                    .catch(() => {});
            }, 7000);
        </script>
    @endpush
@endsection
