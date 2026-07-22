@extends('base')
@section('title','panding')

@section('content')
<div class="max-w-lg mx-auto py-16 text-center mt-30">
    <div class="text-6xl mb-4">⏳</div>
    <h1 class="text-2xl font-bold mb-2">Dossier en cours de traitement</h1>
    <p class="text-gray-500">
        Votre dossier a été soumis le
        {{ $kyc->created_at->format('d/m/Y à H:i') }}.
        Nous vous notifions par WhatsApp dès la validation.
    </p>
    <p class="text-sm text-gray-400 mt-4">Délai : 24 à 48h ouvrables</p>
</div>
@endsection