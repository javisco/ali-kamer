@extends('layouts.admin')
@section('title', 'listes des tutilisateurs')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Utilisateurs</h1>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filtres --}}
            <div class="flex gap-3 mb-6">
                @foreach ([
            '' => 'Tous',
            'buyer' => 'Acheteurs',
            'seller' => 'Vendeurs',
            'secretary' => 'Secrétaires',
        ] as $role => $label)
                    <a href="?role={{ $role }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition
                      {{ request('role') === $role
                          ? 'bg-indigo-600 text-white'
                          : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3">Utilisateur</th>
                            <th class="text-left px-5 py-3">Rôle</th>
                            <th class="text-left px-5 py-3">Statut</th>
                            <th class="text-left px-5 py-3">Inscrit le</th>
                            <th class="text-left px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">

                                {{-- Identité --}}
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900 text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->phone }}</p>
                                    @if ($user->email)
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    @endif
                                </td>

                                {{-- Rôle --}}
                                <td class="px-5 py-4">
                                    @php
                                        $roleConfig = [
                                            'buyer' => ['label' => 'Acheteur', 'class' => 'bg-blue-100 text-blue-700'],
                                            'seller' => [
                                                'label' => 'Vendeur',
                                                'class' => 'bg-indigo-100 text-indigo-700',
                                            ],
                                            'secretary' => [
                                                'label' => 'Secrétaire',
                                                'class' => 'bg-purple-100 text-purple-700',
                                            ],
                                            'admin' => ['label' => 'Admin', 'class' => 'bg-gray-800 text-white'],
                                        ];
                                        $rc = $roleConfig[$user->role] ?? [
                                            'label' => $user->role,
                                            'class' => 'bg-gray-100 text-gray-600',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $rc['class'] }}">
                                        {{ $rc['label'] }}
                                    </span>
                                    {{-- Boutique si vendeur --}}
                                    @if ($user->isSeller() && $user->shop)
                                        <p class="text-xs text-gray-400 mt-1">{{ $user->shop->name }}</p>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    @php
                                        $statusConfig = [
                                            'active' => [
                                                'label' => 'Actif',
                                                'class' => 'bg-emerald-100 text-emerald-700',
                                            ],
                                            'candidate' => [
                                                'label' => 'Candidat',
                                                'class' => 'bg-yellow-100 text-yellow-700',
                                            ],
                                            'suspended' => [
                                                'label' => 'Suspendu',
                                                'class' => 'bg-orange-100 text-orange-700',
                                            ],
                                            'banned' => ['label' => 'Banni', 'class' => 'bg-red-100 text-red-700'],
                                        ];
                                        $sc = $statusConfig[$user->status] ?? [
                                            'label' => $user->status,
                                            'class' => 'bg-gray-100 text-gray-600',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                                        {{ $sc['label'] }}
                                    </span>
                                    {{-- Trust score acheteur --}}
                                    @if ($user->isBuyer())
                                        <p class="text-xs text-gray-400 mt-1">
                                            Score : {{ $user->trust_score }}/100
                                        </p>
                                    @endif
                                </td>

                                {{-- Date inscription --}}
                                <td class="px-5 py-4 text-xs text-gray-400">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Actions --}}
                                {{-- Actions --}}
<td class="px-5 py-4">
    <div class="flex flex-col gap-2">

        {{-- Voir l'historique du Wallet --}}
        <a href="{{ route('admin.users.history', $user) }}" 
           class="text-xs text-indigo-600 hover:underline font-medium flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Historique Wallet
        </a>

        {{-- Bannir / Réactiver --}}
        @if (!$user->isAdmin())
            @if ($user->isBanned())
                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                    @csrf
                    <button class="text-xs text-emerald-600 hover:underline font-medium">
                        Réactiver
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                    onsubmit="return confirm('Bannir {{ $user->name }} ?')">
                    @csrf
                    <button class="text-xs text-orange-600 hover:underline font-medium">
                        Bannir
                    </button>
                </form>
            @endif
        @endif

        {{-- Blacklister (vendeurs uniquement) --}}
        @if ($user->isSeller() && !$user->isBanned())
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="text-xs text-red-600 hover:underline font-medium">
                    Blacklister
                </button>
                <div x-show="open" class="mt-2">
                    <form method="POST"
                        action="{{ route('admin.users.blacklist', $user) }}"
                        class="flex gap-2"
                        onsubmit="return confirm('Blacklister définitivement ?')">
                        @csrf
                        <input type="text" name="reason" required placeholder="Motif..."
                            class="flex-1 border border-gray-300 rounded-lg px-2 py-1 text-xs">
                        <button
                            class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-lg hover:bg-red-700">
                            Confirmer
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    Aucun utilisateur trouvé.
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
