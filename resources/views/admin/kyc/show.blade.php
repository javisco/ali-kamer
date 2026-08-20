@extends('base')

@section('title', 'Examen du dossier KYC')

@section('content')

    <div class="max-w-7xl mx-auto py-8">

        <!-- En-tête -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">

            <div class="flex justify-between items-start">

                <div>

                    <h1 class="text-3xl font-bold text-gray-800">
                        Dossier KYC
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Vérification d'identité du vendeur
                    </p>

                </div>

                <div class="text-right">

                    <h2 class="text-xl font-semibold">
                        {{ $kyc->user->name }}
                    </h2>

                    <p class="text-gray-500">
                        Soumis le
                        {{ $kyc->created_at->format('d/m/Y à H:i') }}
                    </p>

                </div>

            </div>

        </div>

        <!-- Informations -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <div class="lg:col-span-1">

                <div class="bg-white rounded-2xl shadow-lg p-6">

                    <h3 class="text-lg font-bold text-gray-700 mb-5">
                        Informations du vendeur
                    </h3>

                    <div class="space-y-4">

                        <div>

                            <p class="text-sm text-gray-500">
                                Nom
                            </p>

                            <p class="font-semibold">
                                {{ $kyc->user->name }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Téléphone
                            </p>

                            <p class="font-semibold">
                                {{ $kyc->user->phone }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Numéro Mobile Money
                            </p>

                            <p class="font-semibold">
                                {{ $kyc->momo_number }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Date de soumission
                            </p>

                            <p class="font-semibold">
                                {{ $kyc->created_at->format('d/m/Y H:i') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>
            {{-- Vérification MoMo via Campay --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-gray-800 mb-3">Vérification Mobile Money</h2>

                @if ($holderInfo)
                    @php
                        $momoName = $holderInfo['first_name'] ?? '' . ' ' . ($holderInfo['last_name'] ?? '');
                        $momoName = trim($momoName) ?: $holderInfo['name'] ?? 'Non disponible';

                        // Comparer avec le nom sur la CNI
                        $match = str_contains(
                            strtolower($kyc->user->name),
                            strtolower(explode(' ', $momoName)[0] ?? ''),
                        );
                    @endphp

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Numéro MoMo</span>
                            <span class="font-medium">{{ $kyc->momo_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nom enregistré MoMo</span>
                            <span class="font-semibold {{ $match ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $momoName }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nom sur le compte</span>
                            <span class="font-medium">{{ $kyc->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Correspondance</span>
                            <span class="font-bold {{ $match ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $match ? '✓ Correspondance probable' : '⚠ Noms différents' }}
                            </span>
                        </div>
                    </div>

                    @if (!$match)
                        <div class="bg-red-50 border border-red-200 rounded-xl p-3 mt-3 text-xs text-red-700">
                            ⚠ Attention : le nom MoMo ne correspond pas au nom du compte.
                            Vérifiez manuellement avant de valider.
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 rounded-xl p-3 text-sm text-gray-500">
                        Impossible de récupérer les informations MoMo (service indisponible).
                        Vérifiez manuellement.
                    </div>
                @endif
            </div>
            <div class="lg:col-span-2">

                <div class="bg-white rounded-2xl shadow-lg p-6">

                    <h3 class="text-lg font-bold text-gray-700 mb-6">
                        Documents fournis
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <p class="font-medium mb-2">
                                CNI Recto
                            </p>

                            <a href="{{ $urls['cni_front_url'] }}">
                                <img src="{{ $urls['cni_front_url'] }}"
                                    class="rounded-xl border hover:shadow-lg transition cursor-pointer">
                            </a>
                        </div>
                        <div>
                            <p class="font-medium mb-2">
                                CNI Verso
                            </p>
                            <a href="{{ $urls['cni_back_url'] }}">
                                <img src="{{ $urls['cni_back_url'] }}"
                                    class="rounded-xl border hover:shadow-lg transition cursor-pointer">
                            </a>
                        </div>
                        <div>
                            <p class="font-medium mb-2">
                                Selfie avec CNI
                            </p>
                            <a href="{{ $urls['selfie_url'] }}">
                                <img src="{{ $urls['selfie_url'] }}"
                                    class="rounded-xl border hover:shadow-lg transition cursor-pointer">
                            </a>
                        </div>
                        @if ($kyc->rccm_url)
                            <div>
                                <p class="font-medium mb-2">
                                    RCCM
                                </p>
                                <a href="{{ $urls['rccm_url'] }}">
                                    <img src="{{ $urls['rccm_url'] }}"
                                        class="rounded-xl border hover:shadow-lg transition cursor-pointer">
                                </a>

                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        <!-- Décision -->
        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h2 class="text-xl font-bold text-gray-800 mb-6">
                Décision
            </h2>

            <div class="grid lg:grid-cols-2 gap-8">

                <!-- Validation -->

                <form action="{{ route('admin.kyc.approve', $kyc) }}" method="POST">

                    @csrf

                    <button
                        class="w-full rounded-xl bg-green-600 py-4 text-lg font-semibold text-white transition hover:bg-green-700">

                        ✓ Approuver le dossier

                    </button>

                </form>

                <!-- Rejet -->

                <form action="{{ route('admin.kyc.reject', $kyc) }}" method="POST">

                    @csrf

                    <textarea name="reason" rows="4" required placeholder="Expliquez clairement le motif du rejet..."
                        class="w-full rounded-xl border border-gray-300 p-4 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>

                    <button
                        class="mt-4 w-full rounded-xl bg-red-600 py-4 text-lg font-semibold text-white transition hover:bg-red-700">

                        ✗ Rejeter le dossier

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
