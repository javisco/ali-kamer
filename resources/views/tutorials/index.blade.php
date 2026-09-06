@php
    $layout = auth()->user()->isBuyer()
        ? 'layouts.buyer'
        : 'layouts.seller';
@endphp

@extends($layout)
@section('title', 'Tutoriels')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Tutoriels</h1>
    <p class="text-sm text-gray-500 mb-8">
        Apprenez à utiliser Ali-Kamer facilement.
    </p>

    @forelse($tutorials as $category => $items)
        <div class="mb-10">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-1 h-6 bg-indigo-600 rounded-full inline-block"></span>
                {{ App\Models\Tutorial::CATEGORIES[$category] ?? $category }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($items as $tutorial)
                    <a href="{{ route('tutorials.show', $tutorial) }}"
                       class="bg-white rounded-2xl border border-gray-100 shadow-sm
                              hover:shadow-md hover:border-indigo-200 transition overflow-hidden flex">

                        {{-- Thumbnail ou icône --}}
                        <div class="w-24 h-24 bg-indigo-50 flex-shrink-0 flex items-center
                                    justify-center relative">
                            @if($tutorial->thumbnail_url)
                                <img src="{{ $tutorial->thumbnail_url }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl">
                                    {{ $tutorial->type === 'video' ? '🎬' : '📖' }}
                                </span>
                            @endif

                            {{-- Badge vidéo --}}
                            @if($tutorial->type === 'video')
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-10 h-10 bg-black/50 rounded-full flex
                                                items-center justify-center">
                                        <svg class="w-5 h-5 text-white ml-1" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Infos --}}
                        <div class="p-4 flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm line-clamp-2">
                                {{ $tutorial->title }}
                            </p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-400 uppercase">
                                    {{ $tutorial->type === 'video' ? 'Vidéo' : 'Article' }}
                                </span>
                                @if($tutorial->duration_minutes)
                                    <span class="text-xs text-gray-400">
                                        · {{ $tutorial->duration_minutes }} min
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400">Aucun tutoriel disponible pour l'instant.</p>
        </div>
    @endforelse

</div>
</div>
@endsection