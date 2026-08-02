@extends('base')

@section('title', 'Messages')

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Messages</h1>
                @if ($totalUnread > 0)
                    {{-- Badge total messages non lus --}}
                    <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                        {{ $totalUnread }} non lu(s)
                    </span>
                @endif
            </div>

            @if ($conversations->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Aucune conversation</h2>
                    <p class="text-gray-500 text-sm">
                        Contactez un vendeur depuis la fiche d'un produit pour démarrer.
                    </p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($conversations as $conv)
                        @php
                            $user = auth()->user();
                            $unread = $conv->unreadCount($user->id);
                            $partner = $user->isBuyer() ? $conv->shop->name : $conv->buyer->name;
                            $last = $conv->lastMessage;
                        @endphp

                        <a href="{{ route('messaging.show', $conv) }}"
                            class="flex items-center gap-4 bg-white rounded-2xl border
                          {{ $unread > 0 ? 'border-indigo-200' : 'border-gray-100' }}
                          shadow-sm hover:shadow-md transition-all p-4">

                            {{-- Avatar --}}
                            <div
                                class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 font-bold
                                text-lg flex items-center justify-center uppercase flex-shrink-0">
                                {{ substr($partner, 0, 2) }}
                            </div>

                            {{-- Aperçu --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p
                                        class="font-semibold text-gray-900 text-sm {{ $unread > 0 ? 'text-indigo-700' : '' }}">
                                        {{ $partner }}
                                    </p>
                                    @if ($last)
                                        <p class="text-xs text-gray-400 flex-shrink-0 ml-2">
                                            {{ $last->sent_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-0.5">
                                    @if ($last)
                                        <p class="text-xs text-gray-500 truncate">
                                            {{-- Aperçu du dernier message --}}
                                            @if ($last->isText())
                                                {{ Str::limit($last->body, 60) }}
                                            @elseif($last->isImage())
                                                📷 Photo
                                            @else
                                                📄 Document PDF
                                            @endif
                                        </p>
                                    @endif

                                    {{-- Badge messages non lus --}}
                                    @if ($unread > 0)
                                        <span
                                            class="bg-indigo-600 text-white text-xs font-bold
                                             px-2 py-0.5 rounded-full flex-shrink-0 ml-2">
                                            {{ $unread }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-4">{{ $conversations->links() }}</div>
            @endif

        </div>
    </div>
@endsection
