@extends('layouts.admin')

@section('title', 'Gestion des tutoriels - Ali-Kamer')

@section('content')
<div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-xs flex items-center justify-center border border-white/20 shrink-0">
                        <svg class="w-6 h-6 text-[#F9A01B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-white mb-1 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                            Centre de Connaissances
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Tutoriels
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm font-medium">
                            Gérez les contenus d'aide et guides d'utilisation Ali-Kamer.
                        </p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.tutorials.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] text-xs font-black shadow-xs uppercase tracking-wider transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14" />
                        </svg>
                        <span>Nouveau tutoriel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. MESSAGES DE NOTIFICATION -->
        @if(session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-[#E30613]/10 border border-[#E30613]/20 text-[#E30613] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#E30613]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 3. STATISTIQUES -->
        @php
            $allTutorials = $tutorials->flatten();
            $totalTutorials = $allTutorials->count();
            $publishedTutorials = $allTutorials->where('is_published', true)->count();
            $draftTutorials = $totalTutorials - $publishedTutorials;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Total --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-black text-gray-400">Total</p>
                        <p class="text-2xl font-black text-[#0a1b12] mt-0.5">{{ $totalTutorials }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#016837]/10 text-[#016837] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Publiés --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-black text-gray-400">Publiés</p>
                        <p class="text-2xl font-black text-[#016837] mt-0.5">{{ $publishedTutorials }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#016837]/10 text-[#016837] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Brouillons --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-black text-gray-400">Brouillons</p>
                        <p class="text-2xl font-black text-[#0a1b12] mt-0.5">{{ $draftTutorials }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. LISTE PAR CATÉGORIE -->
        @forelse($tutorials as $category => $items)
            <section class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-[#016837]"></span>
                    <h2 class="text-xs font-black text-[#0a1b12] uppercase tracking-wider">
                        {{ App\Models\Tutorial::CATEGORIES[$category] ?? $category }}
                    </h2>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">
                        {{ $items->count() }}
                    </span>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                    {{-- DESKTOP --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-[#F7F7F2] border-b border-gray-200 text-[10px] uppercase tracking-wider font-black text-gray-400">
                                <tr>
                                    <th class="px-5 py-3">Tutoriel</th>
                                    <th class="px-5 py-3">Type</th>
                                    <th class="px-5 py-3">Public</th>
                                    <th class="px-5 py-3">Statut</th>
                                    <th class="px-5 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($items as $tutorial)
                                    <tr class="hover:bg-[#016837]/5 transition">
                                        {{-- Titre --}}
                                        <td class="px-5 py-4">
                                            <div>
                                                <p class="text-xs font-bold text-[#0a1b12]">
                                                    {{ $tutorial->title }}
                                                </p>
                                                @if($tutorial->duration_minutes)
                                                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                                        ⏱ {{ $tutorial->duration_minutes }} minute(s)
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Type --}}
                                        <td class="px-5 py-4">
                                            @if($tutorial->type === 'video')
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-[#016837]">
                                                    🎬 Vidéo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-600">
                                                    📖 Texte
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Public --}}
                                        <td class="px-5 py-4">
                                            <span class="text-xs font-medium text-gray-600">
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
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#016837]/10 border border-[#016837]/20 text-[#016837] text-[10px] font-black uppercase">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#016837]"></span>
                                                    Publié
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-[10px] font-black uppercase">
                                                    Brouillon
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-end gap-1">
                                                {{-- Voir --}}
                                                <a href="{{ route('admin.tutorials.show', $tutorial) }}"
                                                   title="Voir"
                                                   class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-[#016837] hover:bg-[#016837]/10 transition">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                {{-- Modifier --}}
                                                <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                                                   title="Modifier"
                                                   class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-[#016837] hover:bg-[#016837]/10 transition">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                {{-- Toggle --}}
                                                <form method="POST" action="{{ route('admin.tutorials.toggle', $tutorial) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            title="{{ $tutorial->is_published ? 'Masquer' : 'Publier' }}"
                                                            class="w-8 h-8 rounded-lg flex items-center justify-center {{ $tutorial->is_published ? 'text-[#016837] hover:bg-[#016837]/10' : 'text-gray-400 hover:bg-gray-100' }} transition">
                                                        @if($tutorial->is_published)
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>

                                                {{-- Supprimer --}}
                                                <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce tutoriel ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            title="Supprimer"
                                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-[#E30613] hover:bg-[#E30613]/10 transition">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

                    {{-- MOBILE --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($items as $tutorial)
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="text-xs font-bold text-[#0a1b12]">
                                            {{ $tutorial->title }}
                                        </h3>
                                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                            {{ $tutorial->type === 'video' ? '🎬 Vidéo' : '📖 Texte' }}
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
                                        <span class="shrink-0 px-2 py-0.5 rounded-full bg-[#016837]/10 text-[#016837] text-[9px] font-black uppercase">
                                            Publié
                                        </span>
                                    @else
                                        <span class="shrink-0 px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[9px] font-black uppercase">
                                            Brouillon
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-4 gap-1.5 pt-1">
                                    <a href="{{ route('admin.tutorials.show', $tutorial) }}"
                                       class="text-center px-2 py-2 rounded-xl bg-[#016837]/10 text-[#016837] text-[11px] font-bold">
                                        Voir
                                    </a>

                                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                                       class="text-center px-2 py-2 rounded-xl bg-gray-100 text-[#0a1b12] text-[11px] font-bold">
                                        Modifier
                                    </a>

                                    <form method="POST" action="{{ route('admin.tutorials.toggle', $tutorial) }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-center px-2 py-2 rounded-xl bg-[#016837]/10 text-[#016837] text-[11px] font-bold">
                                            {{ $tutorial->is_published ? 'Masquer' : 'Publier' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce tutoriel ?')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full text-center px-2 py-2 rounded-xl bg-[#E30613]/10 text-[#E30613] text-[11px] font-bold">
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
            <!-- 5. AUCUN TUTORIEL -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-8 sm:p-12 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-[#016837]/10 text-[#016837] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                    </svg>
                </div>

                <h2 class="text-base font-black text-[#0a1b12]">
                    Aucun tutoriel pour le moment
                </h2>

                <p class="text-xs text-gray-500 font-medium mt-1 mb-6 max-w-sm mx-auto">
                    Créez votre premier tutoriel pour guider vos acheteurs et vendeurs sur Ali-Kamer.
                </p>

                <a href="{{ route('admin.tutorials.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#F9A01B] hover:bg-[#e08e14] text-[#0a1b12] text-xs font-black uppercase tracking-wider shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14M5 12h14" />
                    </svg>
                    <span>Créer le premier tutoriel</span>
                </a>
            </div>
        @endforelse

    </div>
</div>
@endsection