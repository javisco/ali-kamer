
@extends('layouts.admin')

@section('title', 'Dossier litige')

@section('content')

    {{-- =========================================================
        DOSSIER LITIGE — STYLE ALI-KAMER
    ========================================================== --}}

    <div class="min-h-screen bg-slate-50 py-5 sm:py-6">

        <div class="max-w-5xl mx-auto px-4 sm:px-5">

            {{-- =====================================================
                EN-TÊTE
            ====================================================== --}}
            <div class="flex flex-col sm:flex-row sm:items-center
                        justify-between gap-3 mb-5">

                <div>
                    <div class="flex items-center gap-2 mb-1">

                        <span class="w-2 h-2 rounded-full bg-danger"></span>

                        <span
                            class="text-[10px] font-black uppercase
                                     tracking-[0.16em] text-danger">
                            Centre des litiges
                        </span>

                    </div>

                    <h1 class="text-xl sm:text-2xl font-black
                               text-slate-900 tracking-tight">

                        Dossier litige —
                        {{ $dispute->order->reference }}

                    </h1>

                    <p class="text-xs text-slate-500 mt-1">
                        Analyse et résolution du dossier de commande.
                    </p>
                </div>


                <a href="{{ route('admin.disputes.index') }}"
                    class="inline-flex items-center gap-2 self-start
                          px-3.5 py-2 rounded-xl
                          bg-white border border-slate-200
                          text-xs font-black text-primary-600
                          hover:bg-primary-600/5
                          transition shadow-sm">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                    </svg>

                    Retour aux litiges
                </a>

            </div>


            {{-- =====================================================
                MESSAGE SUCCÈS
            ====================================================== --}}
            @if (session('success'))
                <div
                    class="bg-primary-600/5
                            border border-primary-600/20
                            text-primary-600
                            px-4 py-3 rounded-xl mb-5
                            text-xs sm:text-sm font-semibold
                            flex items-center gap-2">

                    <span
                        class="w-7 h-7 rounded-lg
                                 bg-primary-600/10
                                 flex items-center justify-center
                                 shrink-0">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />

                        </svg>

                    </span>

                    {{ session('success') }}

                </div>
            @endif


            {{-- =====================================================
                CONTENU
            ====================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">


                {{-- =================================================
                    COLONNE PRINCIPALE
                ================================================== --}}
                <div class="lg:col-span-2 space-y-5">


                    {{-- =================================================
                        INFORMATIONS COMMANDE
                    ================================================== --}}
                    <div
                        class="bg-white rounded-2xl
                                border border-slate-200
                                shadow-sm overflow-hidden">

                        <div
                            class="px-5 py-4
                                    border-b border-slate-100
                                    flex items-center gap-3">

                            <div
                                class="w-9 h-9 rounded-xl
                                        bg-primary-600/10
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                                </svg>

                            </div>

                            <div>
                                <h2 class="text-sm font-black text-slate-900">
                                    Commande
                                </h2>

                                <p class="text-[10px] text-slate-400">
                                    Informations de la transaction
                                </p>
                            </div>

                        </div>


                        <div class="p-5 space-y-2.5 text-xs sm:text-sm">

                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-500">Référence</span>

                                <span class="font-black text-primary-600">
                                    {{ $dispute->order->reference }}
                                </span>
                            </div>


                            <div class="h-px bg-slate-100"></div>


                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Acheteur</span>

                                <span class="font-semibold text-slate-700 text-right">
                                    {{ $dispute->order->buyer->name }}
                                    —
                                    {{ $dispute->order->buyer->phone }}
                                </span>
                            </div>


                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Vendeur</span>

                                <span class="font-semibold text-slate-700 text-right">
                                    {{ $dispute->order->shop->name }}
                                </span>
                            </div>


                            <div class="flex justify-between gap-4 pt-1">
                                <span class="text-slate-500">Montant total</span>

                                <span class="font-black text-slate-900">
                                    {{ number_format($dispute->order->total_amount, 0, ',', ' ') }}
                                    FCFA
                                </span>
                            </div>


                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">
                                    Montant net vendeur
                                </span>

                                <span class="font-black text-primary-600">
                                    {{ number_format($dispute->order->net_amount, 0, ',', ' ') }}
                                    FCFA
                                </span>
                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        MOTIF / DESCRIPTION
                    ================================================== --}}
                    <div
                        class="bg-white rounded-2xl
                                border border-slate-200
                                shadow-sm overflow-hidden">

                        <div
                            class="px-5 py-4
                                    border-b border-slate-100
                                    flex items-center gap-3">

                            <div
                                class="w-9 h-9 rounded-xl
                                        bg-danger/10
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.42 0z" />

                                </svg>

                            </div>

                            <div>
                                <h2 class="text-sm font-black text-slate-900">
                                    Litige — {{ $dispute->typeLabel() }}
                                </h2>

                                <p class="text-[10px] text-slate-400">
                                    Motif déclaré par le client
                                </p>
                            </div>

                        </div>


                        <div class="p-5">

                            <p class="text-sm text-slate-700
                                      leading-relaxed">
                                {{ $dispute->description }}
                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                        PREUVES
                    ================================================== --}}
                    @if ($dispute->evidences->count())

                        <div
                            class="bg-white rounded-2xl
                                    border border-slate-200
                                    shadow-sm overflow-hidden">

                            <div
                                class="px-5 py-4
                                        border-b border-slate-100
                                        flex items-center justify-between">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-9 h-9 rounded-xl
                                                bg-accent-500/15
                                                flex items-center justify-center">

                                        <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                        </svg>

                                    </div>

                                    <div>
                                        <h2 class="text-sm font-black text-slate-900">
                                            Preuves
                                        </h2>

                                        <p class="text-[10px] text-slate-400">
                                            {{ $dispute->evidences->count() }}
                                            élément(s) fourni(s)
                                        </p>
                                    </div>

                                </div>

                                <span
                                    class="px-2.5 py-1 rounded-lg
                                             bg-accent-500/10
                                             text-[#9a6200]
                                             text-[10px] font-black">
                                    {{ $dispute->evidences->count() }}
                                </span>

                            </div>


                            <div class="p-5 space-y-3">

                                @foreach ($dispute->evidences as $evidence)
                                    <div
                                        class="p-4
                                                bg-slate-50
                                                rounded-xl
                                                border border-slate-100">

                                        <p
                                            class="text-[10px]
                                                  font-black text-slate-500 mb-2">

                                            {{ $evidence->submitter->name }}

                                            <span class="text-slate-300 mx-1">
                                                •
                                            </span>

                                            {{ $evidence->created_at->format('d/m/Y H:i') }}

                                        </p>


                                        @if ($evidence->isText())
                                            <p
                                                class="text-sm text-slate-700
                                                      italic leading-relaxed">
                                                "{{ $evidence->content }}"
                                            </p>
                                        @elseif($evidence->isPhoto())
                                            <img src="{{ asset('storage/' . $evidence->url) }}"
                                                class="rounded-xl max-w-sm
                                                        cursor-pointer
                                                        border border-slate-200
                                                        shadow-sm
                                                        hover:opacity-90
                                                        transition"
                                                onclick="window.open(this.src)">
                                        @else
                                            <a href="{{ asset('storage/' . $evidence->url) }}" target="_blank"
                                                class="inline-flex items-center gap-2
                                                      text-primary-600
                                                      hover:text-primary-700
                                                      font-bold text-xs
                                                      transition">

                                                <span
                                                    class="w-7 h-7 rounded-lg
                                                             bg-primary-600/10
                                                             flex items-center justify-center">
                                                    📄
                                                </span>

                                                {{ $evidence->description }}

                                            </a>
                                        @endif

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                        CONVERSATION
                    ================================================== --}}

                    {{-- =================================================
    CONVERSATION ACHETEUR / VENDEUR
================================================== --}}
                    <div
                        class="bg-white rounded-2xl
            border border-slate-200
            shadow-sm overflow-hidden">

                        {{-- Header --}}
                        <div class="px-5 py-4 border-b border-slate-100">

                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-9 h-9 rounded-xl
                            bg-primary-600/10
                            flex items-center justify-center">

                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4-4-4z" />

                                        </svg>

                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black text-slate-900">
                                            Historique de la conversation
                                        </h2>

                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            Échanges avant l'ouverture du litige
                                        </p>

                                    </div>

                                </div>

                                {{-- Légende --}}
                                <div class="hidden sm:flex items-center gap-3">

                                    {{-- Acheteur --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                                        <span class="text-[10px] font-bold text-slate-500">
                                            Acheteur
                                        </span>
                                    </div>

                                    {{-- Vendeur --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                                        <span class="text-[10px] font-bold text-slate-500">
                                            Vendeur
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Conversation --}}
                        <div class="p-5">

                            @if ($conversation && $conversation->messages->count())

                                <div
                                    class="max-h-[28rem]
                        overflow-y-auto
                        pr-2
                        space-y-5">

                                    @php
                                        $lastSenderType = null;
                                    @endphp


                                    @foreach ($conversation->messages as $message)
                                        @php
                                            $isBuyer = $message->sender_id === $dispute->order->buyer_id;
                                            $senderType = $isBuyer ? 'buyer' : 'seller';

                                            $senderChanged = $lastSenderType !== $senderType;
                                            $lastSenderType = $senderType;
                                        @endphp


                                        {{-- =================================================
                        GROUPE ACHETEUR
                    ================================================== --}}
                                        @if ($senderChanged)
                                            <div class="flex items-center gap-3 pt-1">

                                                @if ($isBuyer)
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span>

                                                        <span
                                                            class="text-[10px] font-black
                                                 uppercase tracking-wider
                                                 text-primary-600">
                                                            Acheteur
                                                        </span>

                                                        <span class="text-[10px] text-slate-400">
                                                            {{ $dispute->order->buyer->name }}
                                                        </span>
                                                    </div>

                                                    <div class="flex-1 h-px bg-primary-600/10"></div>
                                                @else
                                                    <div class="flex-1 h-px bg-accent-500/20"></div>

                                                    <div class="flex items-center gap-2">

                                                        <span class="text-[10px] text-slate-400">
                                                            {{ $dispute->order->shop->name }}
                                                        </span>

                                                        <span
                                                            class="text-[10px] font-black
                                                 uppercase tracking-wider
                                                 text-[#9a6200]">
                                                            Vendeur
                                                        </span>

                                                        <span class="w-2.5 h-2.5 rounded-full bg-accent-500"></span>

                                                    </div>
                                                @endif

                                            </div>
                                        @endif


                                        {{-- =================================================
                        MESSAGE
                    ================================================== --}}
                                        <div
                                            class="flex
                                {{ $isBuyer ? 'justify-start' : 'justify-end' }}">

                                            <div
                                                class="w-full
                                    {{ $isBuyer ? 'max-w-[85%] sm:max-w-[75%]' : 'max-w-[85%] sm:max-w-[75%]' }}">

                                                {{-- Nom + heure --}}
                                                <div
                                                    class="flex items-center gap-2 mb-1
                                        {{ $isBuyer ? 'justify-start' : 'justify-end' }}">

                                                    @if ($isBuyer)
                                                        <span class="text-[10px] font-bold text-primary-600">
                                                            {{ $message->sender->name }}
                                                        </span>

                                                        <span class="text-[9px] text-slate-400">
                                                            {{ $message->sent_at->format('d/m H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] text-slate-400">
                                                            {{ $message->sent_at->format('d/m H:i') }}
                                                        </span>

                                                        <span class="text-[10px] font-bold text-[#9a6200]">
                                                            {{ $message->sender->name }}
                                                        </span>
                                                    @endif

                                                </div>


                                                {{-- Bulle --}}
                                                <div
                                                    class="px-4 py-3 rounded-2xl text-sm
                                        leading-relaxed
                                        {{ $isBuyer
                                            ? 'bg-primary-600/5 text-slate-800 border border-primary-600/10 rounded-bl-md'
                                            : 'bg-accent-500/10 text-slate-800 border border-accent-500/20 rounded-br-md' }}">

                                                    @if ($message->isText())
                                                        {{ $message->body }}
                                                    @elseif($message->isImage())
                                                        <img src="{{ asset('storage/' . $message->attachment_url) }}"
                                                            class="rounded-xl max-w-xs
                                                cursor-pointer
                                                border border-white/70
                                                shadow-sm
                                                hover:opacity-90
                                                transition"
                                                            onclick="window.open(this.src)">
                                                    @else
                                                        <a href="{{ asset('storage/' . $message->attachment_url) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center gap-2
                                              {{ $isBuyer ? 'text-primary-600' : 'text-[#9a6200]' }}
                                              hover:underline
                                              text-xs font-bold">

                                                            <span
                                                                class="w-7 h-7 rounded-lg
                                                     {{ $isBuyer ? 'bg-primary-600/10' : 'bg-accent-500/15' }}
                                                     flex items-center justify-center">
                                                                📄
                                                            </span>

                                                            {{ $message->attachment_url }}

                                                        </a>
                                                    @endif

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>


                                {{-- =====================================================
                RAPPEL VISUEL
            ====================================================== --}}
                                <div class="mt-5 pt-4 border-t border-slate-100">

                                    <div class="grid grid-cols-2 gap-2">

                                        {{-- Acheteur --}}
                                        <div
                                            class="flex items-center gap-2
                                px-3 py-2 rounded-xl
                                bg-primary-600/5
                                border border-primary-600/10">

                                            <div
                                                class="w-7 h-7 rounded-lg
                                    bg-primary-600/10
                                    flex items-center justify-center">

                                                <svg class="w-3.5 h-3.5 text-primary-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                                </svg>

                                            </div>

                                            <div class="min-w-0">

                                                <p
                                                    class="text-[9px] uppercase
                                      tracking-wider
                                      font-black text-primary-600">
                                                    Acheteur
                                                </p>

                                                <p
                                                    class="text-[10px] font-bold
                                      text-slate-600 truncate">
                                                    {{ $dispute->order->buyer->name }}
                                                </p>

                                            </div>

                                        </div>


                                        {{-- Vendeur --}}
                                        <div
                                            class="flex items-center gap-2
                                px-3 py-2 rounded-xl
                                bg-accent-500/5
                                border border-accent-500/15">

                                            <div
                                                class="w-7 h-7 rounded-lg
                                    bg-accent-500/15
                                    flex items-center justify-center">

                                                <svg class="w-3.5 h-3.5 text-[#9a6200]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8z" />

                                                </svg>

                                            </div>

                                            <div class="min-w-0">

                                                <p
                                                    class="text-[9px] uppercase
                                      tracking-wider
                                      font-black text-[#9a6200]">
                                                    Vendeur
                                                </p>

                                                <p
                                                    class="text-[10px] font-bold
                                      text-slate-600 truncate">
                                                    {{ $dispute->order->shop->name }}
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @else
                                <div
                                    class="bg-slate-50
                        rounded-xl
                        border border-slate-100
                        p-6
                        text-xs text-slate-400
                        text-center">

                                    <div
                                        class="w-10 h-10 mx-auto mb-2
                            rounded-xl
                            bg-slate-100
                            flex items-center justify-center">

                                        💬

                                    </div>

                                    <p class="font-semibold">
                                        Aucune conversation
                                    </p>

                                    <p class="text-[10px] mt-1">
                                        Aucun échange trouvé entre l'acheteur
                                        et le vendeur pour cette boutique.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>



                </div>


                {{-- =================================================
                    COLONNE DÉCISION
                ================================================== --}}
                <div class="space-y-5">

                    @if (!$dispute->isResolved())

                        <div
                            class="bg-white rounded-2xl
                                    border border-slate-200
                                    shadow-sm overflow-hidden">

                            {{-- Header décision --}}
                            <div
                                class="px-5 py-4
                                        bg-slate-900
                                        text-white">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-9 h-9 rounded-xl
                                                bg-accent-500/15
                                                flex items-center justify-center">

                                        <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.052-.382-3.016z" />

                                        </svg>

                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black">
                                            Décision
                                        </h2>

                                        <p class="text-[10px] text-slate-400">
                                            Résoudre définitivement le litige
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}"
                                class="p-5 space-y-4">

                                @csrf


                                {{-- Résolution --}}
                                <div>

                                    <label
                                        class="block text-[10px]
                                                  font-black text-slate-500
                                                  uppercase tracking-wider mb-1.5">

                                        Décision
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="resolution" required
                                        class="w-full
                                                   border border-slate-200
                                                   bg-slate-50
                                                   rounded-xl
                                                   px-3.5 py-2.5
                                                   text-sm
                                                   focus:bg-white
                                                   focus:ring-2
                                                   focus:ring-primary-500/15
                                                   focus:border-primary-600
                                                   outline-none transition">

                                        <option value="">
                                            -- Choisir --
                                        </option>

                                        @foreach (App\Models\Dispute::RESOLUTIONS as $value => $label)
                                            <option value="{{ $value }}">
                                                {{ $label }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- Montant partiel --}}
                                <div>

                                    <label
                                        class="block text-[10px]
                                                  font-black text-slate-500
                                                  uppercase tracking-wider mb-1.5">

                                        Montant remboursement partiel
                                        (FCFA)

                                    </label>

                                    <input type="number" name="resolution_amount" min="0"
                                        placeholder="Laisser vide si non applicable"
                                        class="w-full
                                                  border border-slate-200
                                                  bg-slate-50
                                                  rounded-xl
                                                  px-3.5 py-2.5
                                                  text-sm
                                                  focus:bg-white
                                                  focus:ring-2
                                                  focus:ring-primary-500/15
                                                  focus:border-primary-600
                                                  outline-none transition">

                                </div>


                                {{-- Note --}}
                                <div>

                                    <label
                                        class="block text-[10px]
                                                  font-black text-slate-500
                                                  uppercase tracking-wider mb-1.5">

                                        Note de décision
                                        <span class="text-danger">*</span>

                                    </label>

                                    <textarea name="resolution_note" rows="4" required
                                        class="w-full
                                                     border border-slate-200
                                                     bg-slate-50
                                                     rounded-xl
                                                     px-3.5 py-2.5
                                                     text-sm
                                                     focus:bg-white
                                                     focus:ring-2
                                                     focus:ring-primary-500/15
                                                     focus:border-primary-600
                                                     outline-none transition"
                                        placeholder="Justifiez votre décision..."></textarea>

                                </div>


                                {{-- Bouton --}}
                                <button
                                    class="w-full
                                           bg-danger
                                           hover:bg-[#b80510]
                                           text-white
                                           font-black
                                           py-2.5
                                           rounded-xl
                                           transition
                                           text-xs
                                           shadow-sm">

                                    <span
                                        class="inline-flex items-center
                                                 justify-center gap-2">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />

                                        </svg>

                                        Appliquer la décision

                                    </span>

                                </button>

                            </form>

                        </div>
                    @else
                        {{-- =================================================
                            DÉCISION DÉJÀ APPLIQUÉE
                        ================================================== --}}
                        <div
                            class="bg-primary-600/5
                                    border border-primary-600/20
                                    rounded-2xl
                                    shadow-sm
                                    overflow-hidden">

                            <div
                                class="px-5 py-4
                                        bg-primary-600
                                        text-white">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-9 h-9 rounded-xl
                                                bg-white/15
                                                flex items-center justify-center">

                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />

                                        </svg>

                                    </div>

                                    <div>

                                        <h2 class="text-sm font-black">
                                            Décision appliquée
                                        </h2>

                                        <p class="text-[10px] text-white/70">
                                            Ce dossier est maintenant résolu
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="p-5">

                                <p class="text-sm font-black text-primary-600">
                                    {{ $dispute->resolutionLabel() }}
                                </p>


                                @if ($dispute->resolution_amount)
                                    <div
                                        class="inline-flex items-center
                                                mt-2 px-2.5 py-1 rounded-lg
                                                bg-accent-500/15
                                                text-[#8a5900]
                                                text-xs font-black">

                                        {{ number_format($dispute->resolution_amount, 0, ',', ' ') }}
                                        FCFA

                                    </div>
                                @endif


                                <p
                                    class="text-sm text-slate-700
                                          mt-3 leading-relaxed">
                                    {{ $dispute->resolution_note }}
                                </p>


                                <div
                                    class="mt-3 pt-3
                                            border-t border-primary-600/10">

                                    <p
                                        class="text-[10px]
                                              text-primary-600/70
                                              font-semibold">

                                        Par {{ $dispute->resolver->name }}

                                        <span class="mx-1">—</span>

                                        {{ $dispute->resolved_at->format('d/m/Y H:i') }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection
```
