@php
    $layout = auth()->user()->isBuyer()
        ? 'layouts.buyer'
        : 'layouts.seller';
@endphp

@extends($layout)

@section('title', 'Messages')

@section('content')

<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-primary-600
                            flex items-center justify-center shadow-sm">

                    <svg class="w-5 h-5 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01
                                 M9 16h6m-3 4a9 9 0 100-18
                                 9 9 0 000 18z"/>
                    </svg>

                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase
                              tracking-[0.12em] text-accent-500">
                        Communication
                    </p>

                    <h1 class="text-2xl font-extrabold text-slate-900">
                        Messages
                    </h1>
                </div>

            </div>

            @if ($totalUnread > 0)
                <span class="bg-danger text-white
                             text-xs font-bold px-3 py-1.5
                             rounded-full shadow-sm">
                    {{ $totalUnread }} non lu(s)
                </span>
            @endif

        </div>


        {{-- =========================================================
             AUCUNE CONVERSATION
        ========================================================== --}}
        @if ($conversations->isEmpty())

            <div class="bg-white rounded-2xl border border-slate-100
                        shadow-sm p-12 text-center">

                <div class="w-16 h-16 bg-success-50
                            rounded-2xl flex items-center
                            justify-center mx-auto mb-4">

                    <svg class="w-8 h-8 text-primary-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M8 12h.01M12 12h.01M16 12h.01
                                 M21 12c0 4.418-4.03 8-9 8a9.863
                                 9.863 0 01-4.255-.949L3 20l1.395-3.72
                                 C3.512 15.042 3 13.574 3 12
                                 c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>

                    </svg>

                </div>

                <h2 class="text-lg font-bold text-slate-900 mb-1">
                    Aucune conversation
                </h2>

                <p class="text-slate-500 text-sm">
                    Contactez un vendeur depuis la fiche d'un produit
                    pour démarrer.
                </p>

            </div>

        @else

            {{-- =====================================================
                 LISTE DES CONVERSATIONS
            ====================================================== --}}
            <div class="space-y-2">

                @foreach ($conversations as $conv)

                    @php
                        $user = auth()->user();
                        $unread = $conv->unreadCount($user->id);

                        $partner = $user->isBuyer()
                            ? $conv->shop->name
                            : $conv->buyer->name;

                        $last = $conv->lastMessage;
                    @endphp

                    <a href="{{ route('messaging.show', $conv) }}"
                       class="group flex items-center gap-4 bg-white
                              rounded-2xl border
                              {{ $unread > 0
                                  ? 'border-success-200'
                                  : 'border-slate-100' }}
                              shadow-sm hover:shadow-md
                              transition-all p-4">

                        {{-- Avatar --}}
                        <div class="w-12 h-12 rounded-xl
                                    {{ $unread > 0
                                        ? 'bg-primary-600 text-white'
                                        : 'bg-success-50 text-primary-600' }}
                                    font-bold text-sm
                                    flex items-center justify-center
                                    uppercase flex-shrink-0
                                    group-hover:bg-primary-600
                                    group-hover:text-white
                                    transition-colors">

                            {{ substr($partner, 0, 2) }}

                        </div>


                        {{-- Aperçu --}}
                        <div class="flex-1 min-w-0">

                            <div class="flex items-center justify-between gap-3">

                                <p class="font-semibold text-slate-900 text-sm
                                    {{ $unread > 0 ? 'text-primary-600' : '' }}">

                                    {{ $partner }}

                                </p>

                                @if ($last)
                                    <p class="text-xs text-slate-400 flex-shrink-0">
                                        {{ $last->sent_at->diffForHumans() }}
                                    </p>
                                @endif

                            </div>


                            <div class="flex items-center justify-between mt-0.5">

                                @if ($last)

                                    <p class="text-xs text-slate-500 truncate">

                                        @if ($last->isText())

                                            {{ Str::limit($last->body, 60) }}

                                        @elseif($last->isImage())

                                            📷 Photo

                                        @else

                                            📄 Document PDF

                                        @endif

                                    </p>

                                @endif


                                @if ($unread > 0)

                                    <span class="bg-danger text-white
                                                 text-xs font-bold
                                                 min-w-5 h-5 px-1.5
                                                 rounded-full
                                                 flex items-center
                                                 justify-center
                                                 flex-shrink-0 ml-2">

                                        {{ $unread }}

                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Flèche --}}
                        <span class="text-slate-300
                                     group-hover:text-primary-600
                                     transition-colors">

                            →

                        </span>

                    </a>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="mt-4">
                {{ $conversations->links() }}
            </div>

        @endif

    </div>
</div>

@endsection