@php
    $layout = auth()->user()->isBuyer()
        ? 'layouts.buyer'
        : 'layouts.seller';
@endphp

@extends($layout)
@section('title', $tutorial->title)
@section('content')
    <div class="bg-slate-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                <a href="{{ route('tutorials.index') }}" class="hover:text-primary-600">Tutoriels</a>
                <span>/</span>
                <span>{{ $tutorial->categoryLabel() }}</span>
            </div>

            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">{{ $tutorial->title }}</h1>

            @if ($tutorial->duration_minutes)
                <p class="text-sm text-slate-400 mb-6">⏱ {{ $tutorial->duration_minutes }} minutes</p>
            @endif

            {{-- Vidéo --}}
            @if ($tutorial->type === 'video' && $tutorial->embedUrl())
                <div class="aspect-video rounded-2xl overflow-hidden mb-8 shadow-sm">
                    <iframe src="{{ $tutorial->embedUrl() }}" class="w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
            @endif

            {{-- Contenu texte --}}
            @if ($tutorial->content)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-8 prose prose-sm max-w-none">
                    {!! nl2br(e($tutorial->content)) !!}
                </div>
            @endif

            {{-- Tutoriels similaires --}}
            @if ($related->count())
                <div>
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Dans la même catégorie</h2>
                    <div class="space-y-3">
                        @foreach ($related as $item)
                            <a href="{{ route('tutorials.show', $item) }}"
                                class="flex items-center gap-4 bg-white rounded-xl border border-slate-100
                              shadow-sm p-4 hover:border-primary-200 transition">
                                <span class="text-2xl flex-shrink-0">
                                    {{ $item->type === 'video' ? '🎬' : '📖' }}
                                </span>
                                <div>
                                    <p class="font-medium text-slate-900 text-sm">{{ $item->title }}</p>
                                    @if ($item->duration_minutes)
                                        <p class="text-xs text-slate-400">{{ $item->duration_minutes }} min</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
