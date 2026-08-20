@extends('base')
@section('title', 'Historique — ' . $user->name)
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-gray-400 hover:text-gray-600">←</a>
        <h1 class="text-2xl font-extrabold text-gray-900">
            Transactions — {{ $user->name }}
        </h1>
    </div>

    {{-- Soldes actuels --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">En attente</p>
            <p class="text-xl font-bold text-gray-400">
                {{ number_format($user->wallet_pending, 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-4">
            <p class="text-xs text-indigo-500 mb-1">Disponible</p>
            <p class="text-xl font-bold text-indigo-600">
                {{ number_format($user->wallet_available, 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    @if($transactions->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
            <p class="text-gray-400 text-sm">Aucune transaction.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-50">
                @foreach($transactions as $tx)
                    <div class="flex items-center justify-between px-5 py-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tx->typeLabel() }}
                            </p>
                            @if($tx->note)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $tx->note }}</p>
                            @endif
                            <p class="text-xs text-gray-300 mt-0.5">
                                {{ $tx->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-bold text-sm
                               {{ $tx->isCredit() ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $tx->isCredit() ? '+' : '-' }}
                                {{ number_format($tx->amount, 0, ',', ' ') }} FCFA
                            </p>
                            <p class="text-xs text-gray-300">
                                Solde : {{ number_format($tx->balance_after, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
</div>
@endsection