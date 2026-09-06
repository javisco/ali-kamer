@extends('layouts.admin')

@section('title', 'Liste des utilisateurs - Ali-Kamer')

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-white mb-1 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                            Administration Comptes
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Utilisateurs
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm font-medium">
                            Gérez les comptes, les statuts et suivez l'historique des utilisateurs Ali-Kamer.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. NOTIFICATIONS -->
        @if (session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 3. FILTRES -->
        <div class="flex flex-wrap gap-2">
            @foreach ([
                '' => 'Tous',
                'buyer' => 'Acheteurs',
                'seller' => 'Vendeurs',
                'secretary' => 'Secrétaires',
            ] as $role => $label)
                <a href="?role={{ $role }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition shadow-xs
                   {{ request('role') === $role
                       ? 'bg-[#016837] text-white'
                       : 'bg-white border border-gray-200 text-[#0a1b12] hover:bg-[#016837]/5' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- 4. CONTENANT PRINCIPAL -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
            
            {{-- VUE DESKTOP --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#F7F7F2] border-b border-gray-200 text-[10px] uppercase tracking-wider font-black text-gray-400">
                        <tr>
                            <th class="px-5 py-3">Utilisateur</th>
                            <th class="px-5 py-3">Rôle / Boutique</th>
                            <th class="px-5 py-3">Statut & Trust Score</th>
                            <th class="px-5 py-3">Inscrit le</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-[#016837]/5 transition">

                                {{-- Identité --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-[#0a1b12] text-xs">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $user->phone }}</p>
                                    @if ($user->email)
                                        <p class="text-[11px] text-gray-400">{{ $user->email }}</p>
                                    @endif
                                </td>

                                {{-- Rôle --}}
                                <td class="px-5 py-4">
                                    @php
                                        $roleConfig = [
                                            'buyer' => ['label' => 'Acheteur', 'class' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20'],
                                            'seller' => ['label' => 'Vendeur', 'class' => 'bg-[#F9A01B]/15 text-[#0a1b12] border-[#F9A01B]/30'],
                                            'secretary' => ['label' => 'Secrétaire', 'class' => 'bg-purple-100 text-purple-800 border-purple-200'],
                                            'admin' => ['label' => 'Admin', 'class' => 'bg-[#0a1b12] text-white border-[#0a1b12]'],
                                        ];
                                        $rc = $roleConfig[$user->role] ?? [
                                            'label' => $user->role,
                                            'class' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $rc['class'] }}">
                                        {{ $rc['label'] }}
                                    </span>

                                    @if ($user->isSeller() && $user->shop)
                                        <p class="text-[11px] font-bold text-gray-500 mt-1 flex items-center gap-1">
                                            <span>🏪</span> {{ $user->shop->name }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    @php
                                        $statusConfig = [
                                            'active' => ['label' => 'Actif', 'class' => 'bg-[#016837]/10 text-[#016837] border-[#016837]/20'],
                                            'candidate' => ['label' => 'Candidat', 'class' => 'bg-[#F9A01B]/15 text-[#0a1b12] border-[#F9A01B]/30'],
                                            'suspended' => ['label' => 'Suspendu', 'class' => 'bg-orange-100 text-orange-800 border-orange-200'],
                                            'banned' => ['label' => 'Banni', 'class' => 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20'],
                                        ];
                                        $sc = $statusConfig[$user->status] ?? [
                                            'label' => $user->status,
                                            'class' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $sc['class'] }}">
                                        {{ $sc['label'] }}
                                    </span>

                                    @if ($user->isBuyer())
                                        <p class="text-[11px] font-bold text-gray-500 mt-1">
                                            Score : <span class="text-[#016837]">{{ $user->trust_score }}</span>/100
                                        </p>
                                    @endif
                                </td>

                                {{-- Date d'inscription --}}
                                <td class="px-5 py-4 text-xs font-medium text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-col items-end gap-1.5">

                                        {{-- Wallet --}}
                                        <a href="{{ route('admin.users.history', $user) }}" 
                                           class="inline-flex items-center gap-1 text-[11px] font-black text-[#016837] hover:underline uppercase tracking-wider">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Wallet
                                        </a>

                                        {{-- Bannir / Réactiver --}}
                                        @if (!$user->isAdmin())
                                            @if ($user->isBanned())
                                                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                                    @csrf
                                                    <button class="text-[11px] font-black text-[#016837] hover:underline uppercase tracking-wider">
                                                        Réactiver
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                                                      onsubmit="return confirm('Bannir {{ $user->name }} ?')">
                                                    @csrf
                                                    <button class="text-[11px] font-black text-orange-600 hover:underline uppercase tracking-wider">
                                                        Bannir
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        {{-- Blacklister (vendeurs) --}}
                                        @if ($user->isSeller() && !$user->isBanned())
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open"
                                                        class="text-[11px] font-black text-[#E30613] hover:underline uppercase tracking-wider">
                                                    Blacklister
                                                </button>

                                                <div x-show="open" 
                                                     @click.outside="open = false" 
                                                     x-transition
                                                     class="absolute right-0 mt-2 w-64 bg-white p-3 rounded-xl border border-gray-200 shadow-lg z-20">
                                                    <form method="POST"
                                                          action="{{ route('admin.users.blacklist', $user) }}"
                                                          class="space-y-2"
                                                          onsubmit="return confirm('Blacklister définitivement ?')">
                                                        @csrf
                                                        <input type="text" name="reason" required placeholder="Motif du blacklist..."
                                                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-medium focus:outline-none focus:border-[#016837]">
                                                        <button class="w-full bg-[#E30613] text-white text-xs font-black py-1.5 rounded-lg hover:bg-red-700 uppercase tracking-wider">
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
                                <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-xs font-bold uppercase tracking-wider">
                                    Aucun utilisateur trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- VUE MOBILE --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($users as $user)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-[#0a1b12] text-xs">{{ $user->name }}</p>
                                <p class="text-[11px] text-gray-500 font-medium">{{ $user->phone }}</p>
                                @if ($user->email)
                                    <p class="text-[11px] text-gray-400">{{ $user->email }}</p>
                                @endif
                            </div>

                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $statusConfig[$user->status]['class'] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusConfig[$user->status]['label'] ?? $user->status }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center justify-between text-[11px] pt-1 gap-2">
                            <span class="font-bold text-gray-500">
                                Rôle: <span class="text-[#0a1b12]">{{ $user->role }}</span>
                            </span>

                            @if ($user->isBuyer())
                                <span class="font-bold text-gray-500">
                                    Trust Score: <span class="text-[#016837]">{{ $user->trust_score }}</span>/100
                                </span>
                            @endif

                            <span class="text-gray-400">Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2">
                            <a href="{{ route('admin.users.history', $user) }}" 
                               class="px-2.5 py-1.5 rounded-lg bg-[#016837]/10 text-[#016837] text-[11px] font-bold">
                                Wallet
                            </a>

                            @if (!$user->isAdmin())
                                @if ($user->isBanned())
                                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg bg-[#016837]/10 text-[#016837] text-[11px] font-bold">
                                            Réactiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                                          onsubmit="return confirm('Bannir {{ $user->name }} ?')">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg bg-orange-100 text-orange-800 text-[11px] font-bold">
                                            Bannir
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if ($user->isSeller() && !$user->isBanned())
                                <div x-data="{ open: false }" class="w-full mt-2">
                                    <button @click="open = !open" class="w-full text-center px-2.5 py-1.5 rounded-lg bg-[#E30613]/10 text-[#E30613] text-[11px] font-bold">
                                        Blacklister
                                    </button>

                                    <form x-show="open" method="POST" action="{{ route('admin.users.blacklist', $user) }}" class="mt-2 space-y-2">
                                        @csrf
                                        <input type="text" name="reason" required placeholder="Motif du blacklist..."
                                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs font-medium">
                                        <button class="w-full bg-[#E30613] text-white text-xs font-bold py-1.5 rounded-lg">
                                            Confirmer Blacklist
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-xs font-bold uppercase tracking-wider">
                        Aucun utilisateur trouvé.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- 5. PAGINATION -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection