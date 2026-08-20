@extends('base')
@section('title', 'Historique des transactions')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">
        Historique des transactions
    </h1>

    @if($transactions->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
            <p class="text-gray-400 text-sm">Aucune transaction pour l'instant.</p>
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
                                <p class="text-xs text-gray-400 mt-0.5 truncate">
                                    {{ $tx->note }}
                                </p>
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
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-4">{{ $transactions->links() }}</div>
    @endif

</div>
</div>
@endsection