<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    { $user = Auth::user();
        if ($user->hasRole('admin')) {
            return view('admin.dashboard',compact('user'));
        } else {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à cette page.');
        }
        
    }
}
