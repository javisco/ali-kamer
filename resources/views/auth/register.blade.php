@extends('base')

@section('title', 'register')
@section('content')
    @if (@session('fail'))
        <p class="w-full bg-red-300 mx-auto h-8 mt-2 text-center"> {{ session('fail') }}</p>
    @endif
    <div class="mx-auto shadow-2xl w-md my-5 bg-white rounded-xl  p-3">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <label for="name" class=" block py-1 w-full ">Name</label>
            <input type="text" name="name" id="name" class="  p-1 w-full border rounded-md shadow-2xl"
                value="{{ old('name') }}">
            @error('name')
                <span class="text-red-400 w-full">{{ $message }}</span>
            @enderror
            <label for="email" class=" block py-1 w-full">Email</label>
            <input type="text" name="email" id="email" class="p-1 w-full border rounded-md  shadow-2xl"
                value="{{ old('email') }}">
            @error('email')
                <span class="text-red-400  w-full">{{ $message }}</span>
            @enderror
            <label for="password" class=" block py-1 w-full">password</label>
            <input type="password" name="password" id="password" class="p-1 w-full border rounded-md shadow-2xl"
                value="{{ old('password') }}">
            @error('password')
                <span class="text-red-400  w-full">{{ $message }}</span>
            @enderror
            <label for="password" class=" block py-1 w-full"> confirmer le mot de passe password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="p-1 w-full border rounded-md shadow-2xl " value="{{ old('password_confirmation') }}">
            <label for="phone" class=" block py-1  w-full ">numero de telephone</label>
            <input type="number" name="phone" id="phone" class="p-1 w-full border rounded-md shadow-2xl "
                value="{{ old('phone') }}">
            @error('phone')
                <span class="block text-red-400  w-full">{{ $message }}</span>
            @enderror
            <p class=" text-center mt-2">vous rejoingner la platreforme en tantque :
            </p>
            <div class="flex items-center justify-around mt-1">
                <p>
                    <label for="buyer">acheteur</label>
                    <input type="radio" name="role" id="buyer" value="buyer">
                </p>
                <p>
                    <label for="seller">vendeur</label>
                    <input type="radio" name="role" id="seller" value="seller">
                </p>
            </div>

            <button class="w-full bg-blue-600 my-1 py-1 rounded-md border
                shadow-2xl">valider</button>
        </form>
        <p class="text-gray-900 my-1 p-1 text-center">déjà inscrit ? <a href="{{ route('login.show') }} "
                class="mx-2 text-green-600 font-bold w-4 h-8 rounded "> se connecter</a></p>
    </div>
@endsection
