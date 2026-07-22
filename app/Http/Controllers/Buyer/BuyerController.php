<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function index(){
        $user=Auth::user();
        return view('buyer.dashboard',compact('user'));
    }
}
