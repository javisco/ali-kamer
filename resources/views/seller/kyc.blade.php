@extends('base')
@section('title', 'kyc')

@section('content')
    <div class="max-w-lg mx-auto p-6 rounded-2 m-20  shadow-2xl">

        <h1 class="text-2xl font-bold mb-2">Vérification d'identité de la boutique</h1>
        <p class="text-gray-500 mb-6">
            Déposez vos documents pour activer votre boutique.
            Traitement sous 24 à 48h.
        </p>


        <form method="POST" action="{{ route('seller.kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- CNI Recto --}}

            <label class="block font-medium mb-1">
                CNI Recto <span class="text-red-500">*</span>
            </label>
            <input type="file" name="cni_front_url" accept="image/*" class="w-full border  rounded p-1">
            @error('cni_front')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror


            {{-- CNI Verso --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    CNI Verso <span class="text-red-500">*</span>
                </label>
                <input type="file" name="cni_back_url" accept="image/*" class="w-full border rounded p-1">
                @error('cni_back')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Selfie --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Selfie tenant votre CNI <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-1">
                    Tenez votre CNI face à la caméra, votre visage doit être visible.
                </p>
                <input type="file" name="selfie_url" accept="image/*" class="w-full border rounded p-1">
                @error('selfie')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- RCCM optionnel --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    RCCM ou Carte de contribuable
                    <span class="text-gray-400 font-normal">(optionnel)</span>
                </label>
                <input type="file" name="rccm_url" accept="image/*,application/pdf"
                    class="w-full border border-gray-300 rounded-lg p-2">
            </div>

            {{-- Numéro MoMo --}}


            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700">
                Soumettre mon dossier
            </button>
        </form>
    </div>
@endsection
