<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycRequest;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Sellercontroller extends Controller
{
    public function kycSotre(KycRequest $kycRequest)
    {
        $validatedData = $kycRequest->validated();

        $cni_front_url = $kycRequest->file('cni_front_url')->store('images');
        $validatedData['cni_font_url'] = $cni_front_url;
        $cni_back_url = $kycRequest->file('cni_back_url')->store('images');
        $validatedData['cni_back_url'] = $cni_back_url;
        $selfie_url = $kycRequest->file('selfie_url')->store('images');
        $validatedData['selfie_url'] = $selfie_url;
        $rccm_url = $kycRequest->file('rccm_url')->store('documents');
        $validatedData['rccm_url'] = $rccm_url;

        $user = Auth::user();
        $validatedData['user_id'] = $user->id;

        KycDocument::create($validatedData);
        return view('seller.dashboard',compact('user'));
    }
}
