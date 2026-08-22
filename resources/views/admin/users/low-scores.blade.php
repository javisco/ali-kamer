@extends('base')
@section('title', 'Utilisateurs — Notes basses')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">
            Utilisateurs à surveiller
        </h1>
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-indigo-600 hover:underline">← Tous les utilisateurs</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Distribution des scores --}}
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-center">
            <p class="text-3xl font-extrabold text-red-600">
                {{ $scoreDistribution['critical'] }}
            </p>
            <p class="text-xs text-red-500 mt-1">Score < 20 (Critique)</p>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 text-center">
            <p class="text-3xl font-extrabold text-orange-500">
                {{ $scoreDistribution['low'] }}
            </p>
            <p class="text-xs text-orange-500 mt-1">Score 20-39 (Faible)</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 text-center">
            <p class="text-3xl font-extrabold text-yellow-600">
                {{ $scoreDistribution['medium'] }}
            </p>
            <p class="text-xs text-yellow-600 mt-1">Score 40-69 (Moyen)</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-center">
            <p class="text-3xl font-extrabold text-emerald-600">
                {{ $scoreDistribution['good'] }}
            </p>
            <p class="text-xs text-emerald-600 mt-1">Score ≥ 70 (Fiable)</p>
        </div>
    </div>

    {{-- Filtre par seuil --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.low-scores') }}"
              class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700">
                Afficher les acheteurs avec un score inférieur à :
            </label>
            <input type="number" name="threshold" value="{{ $threshold }}"
                   min="0" max="100"
                   class="w-24 border border-gray-300 rounded-xl px-3 py-2 text-sm
                          focus:ring-2 focus:ring-indigo-500">
            <button class="bg-indigo-600 text-white font-bold px-4 py-2
                           rounded-xl text-sm hover:bg-indigo-700">
                Filtrer
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Acheteur</th>
                    <th class="text-left px-5 py-3">Score</th>
                    <th class="text-left px-5 py-3">Litiges</th>
                    <th class="text-left px-5 py-3">Statut</th>
                    <th class="text-left px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-900 text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full
                                        {{ $user->trust_score < 20 ? 'bg-red-500' :
                                           ($user->trust_score < 40 ? 'bg-orange-400' : 'bg-yellow-400') }}"
                                         style="width: {{ $user->trust_score }}%"></div>
                                </div>
                                <span class="font-bold text-sm
                                    {{ $user->trust_score < 20 ? 'text-red-600' :
                                       ($user->trust_score < 40 ? 'text-orange-500' : 'text-yellow-600') }}">
                                    {{ $user->trust_score }}/100
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="{{ $user->dispute_count > 2 ? 'text-red-500 font-bold' : 'text-gray-600' }}">
                                {{ $user->dispute_count }} litige(s)
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' :
                                   ($user->status === 'banned' ? 'bg-red-100 text-red-700' :
                                   'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                            @if($user->prepayment_required)
                                <span class="ml-1 text-xs text-orange-500">
                                    Prépaiement requis
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1.5">
                                {{-- Voir historique --}}
                                <a href="{{ route('admin.users.history', $user) }}"
                                   class="text-xs text-indigo-600 hover:underline">
                                    Historique
                                </a>

                                {{-- Bannir / Réactiver --}}
                                @if($user->isBanned())
                                    <form method="POST"
                                          action="{{ route('admin.users.unban', $user) }}">
                                        @csrf
                                        <button class="text-xs text-emerald-600 hover:underline">
                                            Réactiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                          action="{{ route('admin.users.ban', $user) }}"
                                          onsubmit="return confirm('Bannir {{ $user->name }} ?')">
                                        @csrf
                                        <button class="text-xs text-red-500 hover:underline">
                                            Bannir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-5 py-10 text-center text-gray-400 text-sm">
                            Aucun utilisateur avec un score inférieur à {{ $threshold }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>

</div>
</div>
@endsection