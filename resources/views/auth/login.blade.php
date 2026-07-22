@extends('base')

@section('title', 'register')
@section('content')

    @if (@session('fail'))
        <div class="text-center bg-red-400 w-full h-10 mt-20 items-center p-2">{{ session('fail') }}</div>
    @endif

    @if (@session('register'))
        <div class="text-center bg-green-400 w-full h-10 mt-20 items-center p-2">{{ session('register') }}</div>
    @endif
    <div class="mx-auto shadow-2xl w-md my-20 bg-white rounded-xl  p-6">
        <form action="{{ route('login') }}" method="POST" class=" ">
            @csrf
            <label for="email" class=" block py-1 w-full">Email</label>
            <input type="text" name="email" id="email" class="p-1 w-full border rounded-md  my-1 shadow-2xl"
                value="{{ old('email') }}">
            @error('email')
                <span class="text-red-400 my-1 w-full">{{ $message }}</span>
            @enderror
            <label for="password" class=" block py-1 w-full">password</label>
            <input type="password" name="password" id="password" class="p-1 w-full border rounded-md shadow-2xl my-1"
                value="{{ old('password') }}">
            @error('password')
                <span class="text-red-400 my-1 w-full">{{ $message }}</span>
            @enderror
            <button class="w-full bg-blue-600 my-2 py-1
                rounded-md border">valider</button>
        </form>
        <a href="{{ route('register.show') }} " class="mx-2  text-green-600 font-bold w-4 h-24 rounded "> s'inscrire</a>
    </div>
@endsection
