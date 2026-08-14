@extends('base')
@section('title', 'Agences partenaires')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Agences partenaires</h1>
        <a href="{{ route('admin.agencies.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold
                  px-5 py-2.5 rounded-xl text-sm transition">
            + Nouvelle agence
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800
                    px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($agencies as $agency)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-bold text-gray-900">{{ $agency->name }}</h2>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                         {{ $agency->is_active
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-red-100 text-red-600' }}">
                                {{ $agency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex gap-4 mt-2 text-xs text-gray-500">
                            <span>{{ $agency->counters_count }} comptoir(s)</span>
                            <span>{{ $agency->cities_count }} ville(s)</span>
                            @if($agency->contact_phone)
                                <span>📞 {{ $agency->contact_phone }}</span>
                            @endif
                        </div>

                        {{-- Villes desservies --}}
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($agency->cities as $city)
                                <span class="px-2 py-0.5 rounded-full text-xs
                                             {{ $city->is_active
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-gray-100 text-gray-400 line-through' }}">
                                    {{ $city->city }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 items-end">
                        <a href="{{ route('admin.agencies.show', $agency) }}"
                           class="text-sm text-indigo-600 hover:underline font-medium">
                            Gérer →
                        </a>
                        <form method="POST"
                              action="{{ route('admin.agencies.toggle', $agency) }}">
                            @csrf
                            <button class="text-xs {{ $agency->is_active ? 'text-orange-500' : 'text-emerald-600' }}
                                           hover:underline">
                                {{ $agency->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <p class="text-gray-400 text-sm">Aucune agence partenaire.</p>
                <a href="{{ route('admin.agencies.create') }}"
                   class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
                    Créer la première agence →
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $agencies->links() }}</div>
</div>
</div>
@endsection