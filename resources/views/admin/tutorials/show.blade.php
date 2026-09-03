@extends('layouts.admin')
@section('title', $tutorial->title)
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">

            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('admin.tutorials.index') }}" class="text-sm text-gray-500 hover:underline">
                    ← Retour à la liste
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2 rounded-xl transition">
                        Modifier
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold
                         {{ $tutorial->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $tutorial->is_published ? 'Publié' : 'Brouillon' }}
                    </span>
                    <span class="text-xs text-gray-400 capitalize">• Pour: {{ $tutorial->role_target }}</span>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $tutorial->title }}</h1>

                @if ($tutorial->video_url)
                    <div class="mb-6 aspect-video bg-black rounded-xl overflow-hidden">
                        @php
                            $embedUrl = str_replace('watch?v=', 'embed/', $tutorial->video_url);
                        @endphp
                        <iframe class="w-full h-full" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif

                @if ($tutorial->content)
                    <div class="prose max-w-none text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                        {{ $tutorial->content }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
