@extends('base')

@section('title', 'Vérification KYC')

@section('content')

    <div class="max-w-3xl mx-auto mt-12">

        @if (session('fail'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-red-700 shadow">
                {{ session('fail') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl bg-white shadow-xl">

            <!-- En-tête -->
            <div class="bg-red-600 px-8 py-6 text-white">

                <h1 class="text-3xl font-bold">
                    Vérification d'identité refusée
                </h1>

                <p class="mt-2 text-red-100">
                    Votre demande de vérification n'a pas pu être validée.
                </p>

            </div>

            <!-- Corps -->
            <div class="space-y-6 p-8">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Bonjour {{ $user->name }},
                    </h2>

                    <p class="mt-3 leading-7 text-gray-600">
                        Après examen de votre dossier, nous ne sommes malheureusement
                        pas en mesure de valider votre identité pour le moment.
                    </p>

                </div>

                <!-- Motif -->
                <div class="rounded-xl border border-red-200 bg-red-50 p-6">

                    <h3 class="mb-3 text-lg font-semibold text-red-700">
                        Motif du rejet
                    </h3>

                    <p class="leading-7 text-gray-700">
                        {{ $user->kycDocument->rejection_reason }}
                    </p>

                </div>

                <!-- Informations -->
                <div class="rounded-xl border bg-gray-50 p-6">

                    <h3 class="mb-3 text-lg font-semibold text-gray-800">
                        Que faire maintenant ?
                    </h3>

                    <ul class="list-disc space-y-2 pl-6 text-gray-600">
                        <li>Vérifiez que les informations saisies sont exactes.</li>
                        <li>Assurez-vous que les documents sont lisibles.</li>
                        <li>Utilisez des photos nettes et complètes.</li>
                        <li>Soumettez une nouvelle demande de vérification.</li>
                    </ul>

                </div>

                <!-- Bouton -->
                <div class="pt-2">

                    <a href="{{ route('seller.kyc.create') }}"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">

                        Soumettre un nouveau dossier

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection
