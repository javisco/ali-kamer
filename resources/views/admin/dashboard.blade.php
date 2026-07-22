@extends('base')

@section('title', 'dashboard')

@section('content')

    @if (@session('succes'))
        <div class="text-center bg-green-500 mx-auto mt-20 w-lg rounded shadow-2xl ">{{ session('succes') }}</div>
    @endif
    <div class="mx-auto w-lg shadow-2xl rounded-xl h-50 m-4 mt-30 p-6">
        <div class="text-2xl text-gray-900 mx-auto m-4 ">espace : {{ $user->role }}</div>
        <div class="text-2xl text-gray-900  mx-auto m-4">{{ $user->name }} bienvenue dans votre dashboard .</div>

        <a href="{{ route('admin.kyc.index') }}" class="text-blue-800 text-2xl  rounded p-2  mx-auto mt-4"> voir les documents
        -></a>
    </div>
@endsection
