@extends('base')

@section('title','show')

@section('content')
    <div class="max-w-4xl mx-auto mt-15 py-8">

        <h1 class="text-2xl font-bold mb-1">Dossier de {{ $kyc->user->name }}</h1>
        <p class="text-gray-500 mb-8">Soumis le {{ $kyc->created_at->format('d/m/Y à H:i') }}</p>

        {{-- Documents --}}
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <p class="text-sm font-medium mb-2">CNI Recto</p>
                <img src="{{ asset('storage/'. $kyc->cni_front_url)}}" class="rounded-lg border border-gray-200 w-full">
            </div>
            <div>
                <p class="text-sm font-medium mb-2">CNI Verso</p>
                <img src="{{ asset('storage/'. $kyc->cni_back_url) }}" class="rounded-lg border border-gray-200 w-full">
            </div>
            <div>
                <p class="text-sm font-medium mb-2">Selfie avec CNI</p>
                <img src="{{ asset('storage/'. $kyc->selfie_url) }}" class="rounded-lg border border-gray-200 w-full">
            </div>
            @if ($urls['rccm'])
                <div>
                    <p class="text-sm font-medium mb-2">RCCM</p>
                    <img src="{{ asset('storage/'. $kyc?->rccm_url) }}" class="rounded-lg border border-gray-200 w-full">
                </div>
            @endif
        </div>

        {{-- Infos --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-8">
            <p><strong>Numéro MoMo :</strong> {{ $kyc->momo_number }}</p>
            <p><strong>Téléphone :</strong> {{ $kyc->user->phone }}</p>
        </div>

        {{-- Actions --}}
        <div class="flex gap-4">
            {{-- Approuver --}}
            <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}">
                @csrf
                <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium">
                    ✓ Approuver
                </button>
            </form>

            {{-- Rejeter --}}
            <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}" class="flex gap-2 flex-1">
                @csrf
                <input type="text" name="reason" required placeholder="Motif du rejet (obligatoire)..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <button class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium">
                    ✗ Rejeter
                </button>
            </form>
        </div>

    </div>
@endsection
