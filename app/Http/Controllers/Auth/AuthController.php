<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function showFormRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {

        if (!$request->validated()) {
            return back()->with('fail', 'verifier les donnees que vous avez entrez')->withInput();
        }

        $validate = $request->validated();
        $validate['password'] = Hash::make($request->password);
        User::create($validate);
        return redirect()->route('login.show')->with('register', "compte creer avec success.connetez-vous pour continuer");
    }


    public function showFormLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    public function login(LoginRequest $request)
    {
        $validate = $request->validated();
        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {

                $user->sendEmailVerificationNotification();

                return redirect()->route('verification.notice');
            }

            return redirect()->route('dashboard');
        }

        return back()->with('fail', 'mot de passe ou email incorrecte');
    }
    public function showDashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'seller')
                return view('blog.sellerDashboard',compact('user'));
            else
                return view('blog.buyerDashboard',compact('user'));
        }
        return route('showFormLogin');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
