<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardService
{

    public function dashboard(User $user)
    {
        if ($user->role === 'buyer') {
            return redirect()->route('buyer.dashboard');
        } else
        if ($user->role === 'seller') {
            $kyc = $user->kycDocument;
            if (!$user->shop) {
                return redirect()->route('seller.shop.create');
            } else
                // if ($user->shop && $user->shop->isActive()) {
                //     return "javisco viens gérer le cas ci.";
                // }
                if ($kyc?->isApproved()) {
                    return redirect()->route('seller.dashboard');
                } else
            if ($kyc?->isPending()) {
                    return redirect()->route('seller.kyc.pending');
                } else
            if ($kyc?->isRejected()) {
                    return redirect()->route('seller.kyc.rejected');
                } else {
                    return redirect()->route('seller.kyc.create');
                }
        } else
        if ($user->role == 'secretary') {
            return redirect()->route('secretary.dashboard');
        } else
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else abort(403, 'vous n\'exister pas');
    }
}
