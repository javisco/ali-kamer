@extends('layouts.admin')

@section('title', $tutorial->title . ' - Ali-Kamer')

@section('content')
<div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-xs flex items-center justify-center border border-white/20 shrink-0">
                        <svg class="w-6 h-6 text-[#F9A01B]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-white mb-1 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                            Aperçu Tutoriel
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Détails du Tutoriel
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm font-medium">
                            Consultez le rendu final et les informations du contenu.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.tutorials.index') }}" 
                       class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Retour</span>
                    </a>

                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] text-xs font-black uppercase tracking-wider shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Modifier</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. CARTE CONTENU DU TUTORIEL -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-5 sm:p-7 space-y-6">
            
            <!-- BADGES ET MÉTADONNÉES -->
            <div class="flex flex-wrap items-center gap-2">
                @if($tutorial->is_published)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#016837]/10 border border-[#016837]/20 text-[#016837] text-xs font-black uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#016837]"></span>
                        Publié
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-500 text-xs font-black uppercase">
                        Brouillon
                    </span>
                @endif

                <span class="px-3 py-1 rounded-full bg-[#F7F7F2] border border-gray-200 text-xs font-bold text-[#0a1b12]">
                    🎯 Public : 
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

                @if($tutorial->type)
                    <span class="px-3 py-1 rounded-full bg-[#F7F7F2] border border-gray-200 text-xs font-bold text-[#0a1b12]">
                        {{ $tutorial->type === 'video' ? '🎬 Vidéo' : '📖 Texte' }}
                    </span>
                @endif

                @if($tutorial->duration_minutes)
                    <span class="px-3 py-1 rounded-full bg-[#F7F7F2] border border-gray-200 text-xs font-bold text-gray-500">
                        ⏱ {{ $tutorial->duration_minutes }} min
                    </span>
                @endif
            </div>

            <!-- TITRE DU TUTORIEL -->
            <h1 class="text-xl sm:text-2xl font-black text-[#0a1b12] leading-tight">
                {{ $tutorial->title }}
            </h1>

            <!-- LECTEUR VIDÉO YOUTUBE (SI VIDÉO) -->
            @if ($tutorial->video_url)
                <div class="aspect-video bg-black rounded-2xl overflow-hidden shadow-xs border border-gray-200">
                    @php
                        $embedUrl = str_replace('watch?v=', 'embed/', $tutorial->video_url);
                    @endphp
                    <iframe class="w-full h-full" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                </div>
            @endif

            <!-- CONTENU TEXTE DU TUTORIEL -->
            @if ($tutorial->content)
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-xs font-black text-[#0a1b12] uppercase tracking-wider mb-3">
                        Description / Instructions
                    </h3>
                    <div class="text-[#0a1b12] text-xs sm:text-sm font-medium leading-relaxed whitespace-pre-line bg-[#F7F7F2] p-4 sm:p-5 rounded-xl border border-gray-200">
                        {{ $tutorial->content }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection