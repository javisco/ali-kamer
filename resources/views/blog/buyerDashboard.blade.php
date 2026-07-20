@extends('base')

@section('title', 'dashboard')

@section('content')

    @if (@session('succes'))
        <div class="text-center bg-green-500 mx-auto mt-10 w-md ">{{ session('succes') }}</div>
    @endif
    <div class="">vous ete acheteur</div>
    <div class="text-2xl text-blue-600  my-20 mx-50  ">{{ $user->name }} bienvenue dans votre dashboard .</div>
@endsection
