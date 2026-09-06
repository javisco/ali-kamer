@extends('layouts.admin')

@section('title', 'Utilisateurs à surveiller - Ali-Kamer')

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-2.5 py-0.5 rounded-full text-[11px] font-semibold text-white mb-1 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-[#E30613]"></span>
                            Surveillance Trust Score
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                            Comptes à Surveiller
                        </h1>
                        <p class="text-white/80 text-xs sm:text-sm font-medium">
                            Analyse des utilisateurs présentant un score de confiance critique ou faible.
                        </p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold px-3.5 py-2 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Tous les utilisateurs</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. NOTIFICATION -->
        @if(session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 3. DISTRIBUTION DES SCORES -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Critique --}}
            <div class="bg-white rounded-2xl border border-[#E30613]/20 shadow-xs p-4 text-center">
                <p class="text-2xl sm:text-3xl font-black text-[#E30613]">
                    {{ $scoreDistribution['critical'] }}
                </p>
                <p class="text-[11px] font-bold text-[#E30613] uppercase tracking-wider mt-1">Score < 20 (Critique)</p>
            </div>

            {{-- Faible --}}
            <div class="bg-white rounded-2xl border border-orange-200 shadow-xs p-4 text-center">
                <p class="text-2xl sm:text-3xl font-black text-orange-600">
                    {{ $scoreDistribution['low'] }}
                </p>
                <p class="text-[11px] font-bold text-orange-600 uppercase tracking-wider mt-1">Score 20-39 (Faible)</p>
            </div>

            {{-- Moyen --}}
            <div class="bg-white rounded-2xl border border-[#F9A01B]/40 shadow-xs p-4 text-center">
                <p class="text-2xl sm:text-3xl font-black text-[#0a1b12]">
                    {{ $scoreDistribution['medium'] }}
                </p>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mt-1">Score 40-69 (Moyen)</p>
            </div>

            {{-- Fiable --}}
            <div class="bg-white rounded-2xl border border-[#016837]/20 shadow-xs p-4 text-center">
                <p class="text-2xl sm:text-3xl font-black text-[#016837]">
                    {{ $scoreDistribution['good'] }}
                </p>
                <p class="text-[11px] font-bold text-[#016837] uppercase tracking-wider mt-1">Score ≥ 70 (Fiable)</p>
            </div>
        </div>

        <!-- 4. FILTRE PAR SEUIL -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.users.low-scores') }}"
                  class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <label class="text-xs font-black text-[#0a1b12] uppercase tracking-wider">
                    Score inférieur à :
                </label>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <input type="number" name="threshold" value="{{ $threshold }}"
                           min="0" max="100"
                           class="w-24 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-[#0a1b12] focus:outline-none focus:border-[#016837] focus:ring-2 focus:ring-[#016837]/20 shadow-xs">
                    <button class="bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 text-[#0a1b12] font-black px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-xs">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- 5. TABLE / CARTE DES UTILISATEURS -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
            
            {{-- VUE DESKTOP --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#F7F7F2] border-b border-gray-200 text-[10px] uppercase tracking-wider font-black text-gray-400">
                        <tr>
                            <th class="px-5 py-3">Acheteur</th>
                            <th class="px-5 py-3">Trust Score</th>
                            <th class="px-5 py-3">Litiges</th>
                            <th class="px-5 py-3">Statut</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-[#016837]/5 transition">
                                
                                {{-- Acheteur --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-[#0a1b12] text-xs">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $user->phone }}</p>
                                </td>

                                {{-- Score --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden border border-gray-200">
                                            <div class="h-full rounded-full
                                                {{ $user->trust_score < 20 ? 'bg-[#E30613]' :
                                                   ($user->trust_score < 40 ? 'bg-orange-500' : 'bg-[#F9A01B]') }}"
                                                 style="width: {{ $user->trust_score }}%"></div>
                                        </div>
                                        <span class="font-black text-xs
                                            {{ $user->trust_score < 20 ? 'text-[#E30613]' :
                                               ($user->trust_score < 40 ? 'text-orange-600' : 'text-[#0a1b12]') }}">
                                            {{ $user->trust_score }}/100
                                        </span>
                                    </div>
                                </td>

                                {{-- Litiges --}}
                                <td class="px-5 py-4 text-xs font-bold">
                                    <span class="{{ $user->dispute_count > 2 ? 'text-[#E30613]' : 'text-gray-600' }}">
                                        {{ $user->dispute_count }} litige(s)
                                    </span>
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border
                                            {{ $user->status === 'active' ? 'bg-[#016837]/10 text-[#016837] border-[#016837]/20' :
                                               ($user->status === 'banned' ? 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20' :
                                               'bg-orange-100 text-orange-800 border-orange-200') }}">
                                            {{ ucfirst($user->status) }}
                                        </span>

                                        @if($user->prepayment_required)
                                            <span class="text-[10px] font-black text-orange-600 uppercase tracking-wider">
                                                ⚠ Prépaiement requis
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-col items-end gap-1.5">
                                        <a href="{{ route('admin.users.history', $user) }}"
                                           class="text-[11px] font-black text-[#016837] hover:underline uppercase tracking-wider">
                                            Historique
                                        </a>

                                        @if($user->isBanned())
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
                                                <button class="text-[11px] font-black text-[#E30613] hover:underline uppercase tracking-wider">
                                                    Bannir
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-xs font-bold uppercase tracking-wider">
                                    Aucun utilisateur avec un score inférieur à {{ $threshold }}.
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
                            </div>

                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border
                                {{ $user->status === 'active' ? 'bg-[#016837]/10 text-[#016837] border-[#016837]/20' :
                                   ($user->status === 'banned' ? 'bg-[#E30613]/10 text-[#E30613] border-[#E30613]/20' :
                                   'bg-orange-100 text-orange-800 border-orange-200') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 pt-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-500">Trust Score :</span>
                                <span class="font-black {{ $user->trust_score < 20 ? 'text-[#E30613]' : ($user->trust_score < 40 ? 'text-orange-600' : 'text-[#0a1b12]') }}">
                                    {{ $user->trust_score }}/100
                                </span>
                            </div>

                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden border border-gray-200">
                                <div class="h-full rounded-full {{ $user->trust_score < 20 ? 'bg-[#E30613]' : ($user->trust_score < 40 ? 'bg-orange-500' : 'bg-[#F9A01B]') }}"
                                     style="width: {{ $user->trust_score }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] pt-1">
                            <span class="font-bold {{ $user->dispute_count > 2 ? 'text-[#E30613]' : 'text-gray-600' }}">
                                {{ $user->dispute_count }} litige(s)
                            </span>

                            @if($user->prepayment_required)
                                <span class="text-[10px] font-black text-orange-600 uppercase tracking-wider">
                                    ⚠ Prépaiement requis
                                </span>
                            @endif
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between gap-2">
                            <a href="{{ route('admin.users.history', $user) }}"
                               class="px-2.5 py-1.5 rounded-lg bg-[#016837]/10 text-[#016837] text-[11px] font-bold">
                                Historique
                            </a>

                            @if($user->isBanned())
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
                                    <button class="px-2.5 py-1.5 rounded-lg bg-[#E30613]/10 text-[#E30613] text-[11px] font-bold">
                                        Bannir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-xs font-bold uppercase tracking-wider">
                        Aucun utilisateur avec un score inférieur à {{ $threshold }}.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- 6. PAGINATION -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection