@extends('base')
@section('title', 'Tutoriels')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Tutoriels</h1>
        <a href="{{ route('admin.tutorials.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                  px-5 py-2.5 rounded-xl text-sm transition">
            + Nouveau tutoriel
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800
                    px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @forelse($tutorials as $category => $items)
        <div class="mb-8">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">
                {{ App\Models\Tutorial::CATEGORIES[$category] ?? $category }}
            </h2>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="text-left px-5 py-3">Titre</th>
                            <th class="text-left px-5 py-3">Type</th>
                            <th class="text-left px-5 py-3">Pour</th>
                            <th class="text-left px-5 py-3">Statut</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($items as $tutorial)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-sm font-medium">{{ $tutorial->title }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">
                                    {{ $tutorial->type === 'video' ? '🎬 Vidéo' : '📖 Texte' }}
                                    @if($tutorial->duration_minutes)
                                        <span class="text-xs text-gray-400">
                                            ({{ $tutorial->duration_minutes }} min)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500 capitalize">
                                    {{ $tutorial->role_target === 'all' ? 'Tous' : $tutorial->role_target }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                                 {{ $tutorial->is_published
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : 'bg-gray-100 text-gray-500' }}">
                                        {{ $tutorial->is_published ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.tutorials.edit', $tutorial) }}"
                                           class="text-sm text-indigo-600 hover:underline">
                                            Modifier
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.tutorials.toggle', $tutorial) }}">
                                            @csrf
                                            <button class="text-sm text-gray-500 hover:underline">
                                                {{ $tutorial->is_published ? 'Dépublier' : 'Publier' }}
                                            </button>
                                        </form>
                                        <form method="POST"
                                              action="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                              onsubmit="return confirm('Supprimer ?')">
                                            @csrf @method('DELETE')
                                            <button class="text-sm text-red-500 hover:underline">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">Aucun tutoriel. Créez le premier.</p>
        </div>
    @endforelse

</div>
</div>
@endsection