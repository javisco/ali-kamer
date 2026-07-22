@extends('base')

@section('title', 'Vérification KYC')

@section('content')

<div class="max-w-5xl mx-auto py-10">

    <!-- En-tête -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Vérification de votre identité
        </h1>

        <p class="mt-3 text-gray-600 leading-7">
            Afin d'assurer la sécurité des acheteurs et des vendeurs,
            nous devons vérifier votre identité avant l'activation de votre boutique.
        </p>

        <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-5">

            <h2 class="font-semibold text-blue-700 mb-2">
                Informations importantes
            </h2>

            <ul class="list-disc pl-6 space-y-2 text-gray-700">

                <li>Les photos doivent être nettes et bien éclairées.</li>

                <li>Les quatre coins du document doivent être visibles.</li>

                <li>Le selfie doit montrer clairement votre visage ainsi que votre pièce d'identité.</li>

                <li>Le traitement prend généralement entre <strong>24 et 48 heures</strong>.</li>

            </ul>

        </div>

    </div>

    <!-- Formulaire -->

    <form
        action="{{ route('seller.kyc.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-2xl shadow-lg p-8 space-y-8">

        @csrf

        <!-- Recto -->

        <div>

            <label class="block font-semibold text-gray-700 mb-2">

                CNI - Recto
                <span class="text-red-500">*</span>

            </label>

            <input
                type="file"
                name="cni_front_url"
                accept="image/*"
                class="w-full rounded-lg border border-gray-300 p-3
                focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('cni_front_url')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Verso -->

        <div>

            <label class="block font-semibold text-gray-700 mb-2">

                CNI - Verso
                <span class="text-red-500">*</span>

            </label>

            <input
                type="file"
                name="cni_back_url"
                accept="image/*"
                class="w-full rounded-lg border border-gray-300 p-3
                focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('cni_back_url')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Selfie -->

        <div>

            <label class="block font-semibold text-gray-700 mb-2">

                Selfie avec votre CNI
                <span class="text-red-500">*</span>

            </label>

            <p class="text-sm text-gray-500 mb-3">

                Prenez une photo où votre visage et votre pièce d'identité sont clairement visibles.

            </p>

            <input
                type="file"
                name="selfie_url"
                accept="image/*"
                class="w-full rounded-lg border border-gray-300 p-3
                focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('selfie_url')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- RCCM -->

        <div>

            <label class="block font-semibold text-gray-700 mb-2">

                RCCM ou Carte de contribuable

                <span class="text-gray-400 text-sm">
                    (Facultatif)
                </span>

            </label>

            <input
                type="file"
                name="rccm_url"
                accept="image/*,application/pdf"
                class="w-full rounded-lg border border-gray-300 p-3
                focus:outline-none focus:ring-2 focus:ring-blue-500">

            @error('rccm_url')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <!-- Bouton -->

        <button
            type="submit"
            class="w-full rounded-xl bg-blue-600 py-4 text-lg font-semibold
            text-white transition hover:bg-blue-700">

            Soumettre mon dossier

        </button>

    </form>

</div>

@endsection