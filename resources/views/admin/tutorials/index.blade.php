@extends('layouts.admin')

@section('title', 'Gestion des tutoriels - Ali-Kamer')

@section('content')

<div class="min-h-screen bg-slate-50">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="bg-white border-b border-slate-200">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">

            <div class="flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-4">

                {{-- Titre --}}
                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl
                                bg-blue-600
                                flex items-center justify-center
                                shadow-lg shadow-blue-600/20">

                        <svg class="w-5 h-5 text-white"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 6.253v13m0-13C10.832 5.477
                                     9.246 5 7.5 5S4.168 5.477
                                     3 6.253v13C4.168 18.477
                                     5.754 18 7.5 18s3.332.477
                                     4.5 1.253m0-13C13.168 5.477
                                     14.754 5 16.5 5c1.746 0
                                     3.332.477 4.5 1.253v13
                                     C19.832 18.477 18.246 18
                                     16.5 18c-1.746 0-3.332.477
                                     -4.5 1.253"/>

                        </svg>

                    </div>

                    <div>

                        <h1 class="text-xl sm:text-2xl
                                   font-black text-slate-900">

                            Tutoriels

                        </h1>

                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">

                            Gérez les contenus d'aide d'Ali-Kamer.

                        </p>

                    </div>

                </div>


                {{-- =================================================
                     BOUTON TOUJOURS VISIBLE
                ================================================== --}}

                <a href="{{ route('admin.tutorials.create') }}"
                   class="inline-flex items-center justify-center
                          gap-2
                          px-4 py-2.5
                          rounded-xl
                          bg-blue-600
                          hover:bg-blue-700
                          active:scale-[0.98]
                          text-white
                          text-sm
                          font-bold
                          shadow-lg shadow-blue-600/20
                          transition-all">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M12 5v14M5 12h14"/>

                    </svg>

                    Nouveau tutoriel

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CONTENU
    ========================================================== --}}

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


        {{-- =====================================================
             MESSAGES
        ====================================================== --}}

        @if(session('success'))

            <div class="mb-5 flex items-center gap-3
                        px-4 py-3
                        rounded-xl
                        bg-emerald-50
                        border border-emerald-200
                        text-emerald-800
                        text-sm">

                <div class="w-7 h-7 rounded-lg
                            bg-emerald-100
                            flex items-center justify-center">

                    <svg class="w-4 h-4 text-emerald-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                </div>

                <span class="font-medium">
                    {{ session('success') }}
                </span>

            </div>

        @endif


        @if(session('error'))

            <div class="mb-5 px-4 py-3
                        rounded-xl
                        bg-red-50
                        border border-red-200
                        text-red-700
                        text-sm">

                {{ session('error') }}

            </div>

        @endif


        {{-- =====================================================
             STATISTIQUES
        ====================================================== --}}

        @php
            $allTutorials = $tutorials->flatten();

            $totalTutorials = $allTutorials->count();

            $publishedTutorials = $allTutorials
                ->where('is_published', true)
                ->count();

            $draftTutorials = $totalTutorials - $publishedTutorials;
        @endphp


        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">

            {{-- Total --}}
            <div class="bg-white
                        border border-slate-200
                        rounded-2xl
                        p-4
                        shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-[10px]
                                  uppercase
                                  tracking-wider
                                  font-black
                                  text-slate-400">

                            Total

                        </p>

                        <p class="text-2xl
                                  font-black
                                  text-slate-900
                                  mt-1">

                            {{ $totalTutorials }}

                        </p>

                    </div>

                    <div class="w-10 h-10
                                rounded-xl
                                bg-blue-50
                                text-blue-600
                                flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 6.253v13m0-13C10.832
                                     5.477 9.246 5 7.5 5S4.168
                                     5.477 3 6.253v13C4.168
                                     18.477 5.754 18 7.5 18s3.332
                                     .477 4.5 1.253"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- Publiés --}}
            <div class="bg-white
                        border border-slate-200
                        rounded-2xl
                        p-4
                        shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-[10px]
                                  uppercase
                                  tracking-wider
                                  font-black
                                  text-slate-400">

                            Publiés

                        </p>

                        <p class="text-2xl
                                  font-black
                                  text-emerald-600
                                  mt-1">

                            {{ $publishedTutorials }}

                        </p>

                    </div>

                    <div class="w-10 h-10
                                rounded-xl
                                bg-emerald-50
                                text-emerald-600
                                flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7"/>

                        </svg>

                    </div>

                </div>

            </div>


            {{-- Brouillons --}}
            <div class="bg-white
                        border border-slate-200
                        rounded-2xl
                        p-4
                        shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-[10px]
                                  uppercase
                                  tracking-wider
                                  font-black
                                  text-slate-400">

                            Brouillons

                        </p>

                        <p class="text-2xl
                                  font-black
                                  text-slate-700
                                  mt-1">

                            {{ $draftTutorials }}

                        </p>

                    </div>

                    <div class="w-10 h-10
                                rounded-xl
                                bg-slate-100
                                text-slate-500
                                flex items-center justify-center">

                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M9 5h6m-7 4h8m-8 4h8m-8 4h5
                                     M5 3h14a2 2 0 012 2v14a2 2
                                     0 01-2 2H5a2 2 0 01-2-2V5
                                     a2 2 0 012-2z"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             LISTE PAR CATÉGORIE
        ====================================================== --}}

        @forelse($tutorials as $category => $items)

            <section class="mb-7">

                <div class="flex items-center gap-2 mb-3">

                    <span class="w-1.5 h-5
                                 rounded-full
                                 bg-blue-600">
                    </span>

                    <h2 class="text-sm
                               font-black
                               text-slate-700
                               uppercase
                               tracking-wider">

                        {{ App\Models\Tutorial::CATEGORIES[$category] ?? $category }}

                    </h2>

                    <span class="text-[10px]
                                 font-bold
                                 px-2 py-0.5
                                 rounded-full
                                 bg-slate-100
                                 text-slate-500">

                        {{ $items->count() }}

                    </span>

                </div>


                <div class="bg-white
                            rounded-2xl
                            border border-slate-200
                            shadow-sm
                            overflow-hidden">

                    {{-- Desktop --}}
                    <div class="hidden md:block overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-slate-50
                                          border-b border-slate-200">

                                <tr>

                                    <th class="text-left
                                               px-5 py-3
                                               text-[10px]
                                               uppercase
                                               tracking-wider
                                               font-black
                                               text-slate-400">

                                        Tutoriel

                                    </th>

                                    <th class="text-left
                                               px-5 py-3
                                               text-[10px]
                                               uppercase
                                               tracking-wider
                                               font-black
                                               text-slate-400">

                                        Type

                                    </th>

                                    <th class="text-left
                                               px-5 py-3
                                               text-[10px]
                                               uppercase
                                               tracking-wider
                                               font-black
                                               text-slate-400">

                                        Public

                                    </th>

                                    <th class="text-left
                                               px-5 py-3
                                               text-[10px]
                                               uppercase
                                               tracking-wider
                                               font-black
                                               text-slate-400">

                                        Statut

                                    </th>

                                    <th class="px-5 py-3 text-right
                                               text-[10px]
                                               uppercase
                                               tracking-wider
                                               font-black
                                               text-slate-400">

                                        Actions

                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100">

                                @foreach($items as $tutorial)

                                    <tr class="hover:bg-blue-50/40
                                               transition">

                                        {{-- Titre --}}
                                        <td class="px-5 py-4">

                                            <div>

                                                <p class="text-sm
                                                          font-bold
                                                          text-slate-800">

                                                    {{ $tutorial->title }}

                                                </p>

                                                @if($tutorial->duration_minutes)

                                                    <p class="text-[11px]
                                                              text-slate-400
                                                              mt-0.5">

                                                        {{ $tutorial->duration_minutes }}
                                                        minute(s)

                                                    </p>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- Type --}}
                                        <td class="px-5 py-4">

                                            @if($tutorial->type === 'video')

                                                <span class="inline-flex
                                                             items-center gap-1.5
                                                             text-xs
                                                             font-bold
                                                             text-blue-600">

                                                    🎬 Vidéo

                                                </span>

                                            @else

                                                <span class="inline-flex
                                                             items-center gap-1.5
                                                             text-xs
                                                             font-bold
                                                             text-slate-600">

                                                    📖 Texte

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Public --}}
                                        <td class="px-5 py-4">

                                            <span class="text-xs
                                                         font-semibold
                                                         text-slate-500">

                                                @if($tutorial->role_target === 'all')
                                                    Tous
                                                @elseif($tutorial->role_target === 'buyer')
                                                    Acheteurs
                                                @elseif($tutorial->role_target === 'seller')
                                                    Vendeurs
                                                @else
                                                    {{ ucfirst($tutorial->role_target) }}
                                                @endif

                                            </span>

                                        </td>


                                        {{-- Statut --}}
                                        <td class="px-5 py-4">

                                            @if($tutorial->is_published)

                                                <span class="inline-flex
                                                             items-center gap-1.5
                                                             px-2.5 py-1
                                                             rounded-full
                                                             bg-emerald-50
                                                             border border-emerald-100
                                                             text-emerald-700
                                                             text-[10px]
                                                             font-black">

                                                    <span class="w-1.5 h-1.5
                                                                 rounded-full
                                                                 bg-emerald-500">
                                                    </span>

                                                    Publié

                                                </span>

                                            @else

                                                <span class="inline-flex
                                                             items-center gap-1.5
                                                             px-2.5 py-1
                                                             rounded-full
                                                             bg-slate-100
                                                             text-slate-500
                                                             text-[10px]
                                                             font-black">

                                                    Brouillon

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="px-5 py-4">

                                            <div class="flex items-center
                                                        justify-end
                                                        gap-1.5">

                                                {{-- Voir --}}
                                                <a href="{{ route('admin.tutorials.show', $tutorial) }}"
                                                   title="Voir"
                                                   class="w-8 h-8
                                                          rounded-lg
                                                          flex items-center
                                                          justify-center
                                                          text-slate-500
                                                          hover:text-blue-600
                                                          hover:bg-blue-50
                                                          transition">

                                                    <svg class="w-4 h-4"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="1.8"
                                                              d="M2.458 12C3.732
                                                                 7.943 7.523 5
                                                                 12 5c4.478 0
                                                                 8.268 2.943
                                                                 9.542 7-1.274
                                                                 4.057-5.064
                                                                 7-9.542 7-4.477
                                                                 0-8.268-2.943
                                                                 -9.542-7z"/>

                                                        <circle cx="12"
                                                                cy="12"
                                                                r="3"/>

                                                    </svg>

                                                </a>


                                                {{-- Modifier --}}
                                                <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                                                   title="Modifier"
                                                   class="w-8 h-8
                                                          rounded-lg
                                                          flex items-center
                                                          justify-center
                                                          text-slate-500
                                                          hover:text-blue-600
                                                          hover:bg-blue-50
                                                          transition">

                                                    <svg class="w-4 h-4"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="1.8"
                                                              d="M11 4H6a2 2 0
                                                                 00-2 2v12a2 2
                                                                 0 002 2h12a2
                                                                 2 0 002-2v-5
                                                                 M18.5 2.5a2.121
                                                                 2.121 0 013
                                                                 3L12 15l-4
                                                                 1 1-1-4
                                                                 9.5-9.5z"/>

                                                    </svg>

                                                </a>


                                                {{-- Toggle --}}
                                                <form method="POST"
                                                      action="{{ route('admin.tutorials.toggle', $tutorial) }}">

                                                    @csrf

                                                    <button type="submit"
                                                            title="{{ $tutorial->is_published ? 'Dépublier' : 'Publier' }}"
                                                            class="w-8 h-8
                                                                   rounded-lg
                                                                   flex items-center
                                                                   justify-center
                                                                   {{ $tutorial->is_published
                                                                        ? 'text-emerald-600 hover:bg-emerald-50'
                                                                        : 'text-slate-500 hover:bg-slate-100' }}
                                                                   transition">

                                                        @if($tutorial->is_published)

                                                            <svg class="w-4 h-4"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 viewBox="0 0 24 24">

                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M5 13l4 4L19 7"/>

                                                            </svg>

                                                        @else

                                                            <svg class="w-4 h-4"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 viewBox="0 0 24 24">

                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="1.8"
                                                                      d="M12 6v12m6-6H6"/>

                                                            </svg>

                                                        @endif

                                                    </button>

                                                </form>


                                                {{-- Supprimer --}}
                                                <form method="POST"
                                                      action="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce tutoriel ?')">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            title="Supprimer"
                                                            class="w-8 h-8
                                                                   rounded-lg
                                                                   flex items-center
                                                                   justify-center
                                                                   text-red-500
                                                                   hover:bg-red-50
                                                                   transition">

                                                        <svg class="w-4 h-4"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="1.8"
                                                                  d="M6 7h12m-9 0V5a1 1
                                                                     0 011-1h2a1 1
                                                                     0 011 1v2m2
                                                                     0v12a2 2
                                                                     0 01-2 2H8a2 2
                                                                     0 01-2-2V7m3
                                                                     3v6m4-6v6"/>

                                                        </svg>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         MOBILE
                    ================================================== --}}

                    <div class="md:hidden divide-y divide-slate-100">

                        @foreach($items as $tutorial)

                            <div class="p-4">

                                <div class="flex items-start
                                            justify-between gap-3">

                                    <div class="min-w-0">

                                        <h3 class="text-sm
                                                   font-bold
                                                   text-slate-800">

                                            {{ $tutorial->title }}

                                        </h3>

                                        <p class="text-xs
                                                  text-slate-400 mt-1">

                                            {{ $tutorial->type === 'video'
                                                ? '🎬 Vidéo'
                                                : '📖 Texte' }}

                                            ·

                                            @if($tutorial->role_target === 'all')
                                                Tous
                                            @elseif($tutorial->role_target === 'buyer')
                                                Acheteurs
                                            @elseif($tutorial->role_target === 'seller')
                                                Vendeurs
                                            @else
                                                {{ ucfirst($tutorial->role_target) }}
                                            @endif

                                        </p>

                                    </div>

                                    @if($tutorial->is_published)

                                        <span class="shrink-0
                                                     px-2 py-1
                                                     rounded-full
                                                     bg-emerald-50
                                                     text-emerald-700
                                                     text-[9px]
                                                     font-black">

                                            Publié

                                        </span>

                                    @else

                                        <span class="shrink-0
                                                     px-2 py-1
                                                     rounded-full
                                                     bg-slate-100
                                                     text-slate-500
                                                     text-[9px]
                                                     font-black">

                                            Brouillon

                                        </span>

                                    @endif

                                </div>


                                <div class="flex items-center gap-2 mt-4">

                                    <a href="{{ route('admin.tutorials.show', $tutorial) }}"
                                       class="flex-1 text-center
                                              px-3 py-2
                                              rounded-lg
                                              bg-blue-50
                                              text-blue-600
                                              text-xs
                                              font-bold">

                                        Voir

                                    </a>

                                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                                       class="flex-1 text-center
                                              px-3 py-2
                                              rounded-lg
                                              bg-slate-100
                                              text-slate-600
                                              text-xs
                                              font-bold">

                                        Modifier

                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.tutorials.toggle', $tutorial) }}">

                                        @csrf

                                        <button type="submit"
                                                class="px-3 py-2
                                                       rounded-lg
                                                       bg-emerald-50
                                                       text-emerald-600
                                                       text-xs
                                                       font-bold">

                                            {{ $tutorial->is_published ? 'Masquer' : 'Publier' }}

                                        </button>

                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce tutoriel ?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2
                                                       rounded-lg
                                                       bg-red-50
                                                       text-red-600
                                                       text-xs
                                                       font-bold">

                                            Suppr.

                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </section>

        @empty

            {{-- =====================================================
                 AUCUN TUTORIEL
            ====================================================== --}}

            <div class="bg-white
                        border border-slate-200
                        rounded-2xl
                        shadow-sm
                        p-10 sm:p-14
                        text-center">

                <div class="w-16 h-16
                            mx-auto
                            rounded-2xl
                            bg-blue-50
                            text-blue-600
                            flex items-center justify-center
                            mb-5">

                    <svg class="w-8 h-8"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832
                                 5.477 9.246 5 7.5 5S4.168
                                 5.477 3 6.253v13C4.168
                                 18.477 5.754 18 7.5 18s3.332
                                 .477 4.5 1.253"/>

                    </svg>

                </div>

                <h2 class="text-lg
                           font-black
                           text-slate-800">

                    Aucun tutoriel

                </h2>

                <p class="text-sm
                          text-slate-500
                          mt-1 mb-6">

                    Commencez par créer votre premier tutoriel.

                </p>

                {{-- Le bouton reste présent même ici --}}
                <a href="{{ route('admin.tutorials.create') }}"
                   class="inline-flex items-center gap-2
                          px-5 py-2.5
                          rounded-xl
                          bg-blue-600
                          hover:bg-blue-700
                          text-white
                          text-sm
                          font-bold
                          shadow-lg shadow-blue-600/20
                          transition">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M12 5v14M5 12h14"/>

                    </svg>

                    Créer le premier tutoriel

                </a>

            </div>

        @endforelse

    </main>

</div>

@endsection