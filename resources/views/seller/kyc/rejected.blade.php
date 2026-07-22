@extends('base')

@section('title', 'dashboard')

@section('content')




    @if (@session('fail'))
        <div class="text-center bg-green-500 mx-auto mt-30 w-md ">{{ session('fail') }}</div>
    @endif
    <div class=" w-md mx-auto h-60 shadow-2xl rounded ">monsieur/ madame : {{ $user->name }} votre dossier a ete rejeter pour les raison suivante</div>

@endsection
