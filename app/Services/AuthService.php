<?php

namespace App\Services;

use App\Models\Blacklist;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    // ────────────────────────────────────────────────────────────────
    // HELPERS PRIVÉS
    // ────────────────────────────────────────────────────────────────

    public function checkBlacklist(string $phone, ?string $phoneMomo = null): void
    {
        $query = Blacklist::where('phone_number', $phone);

        if ($phoneMomo) {
            $query->orWhere('phone_momo', $phoneMomo);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Ce numéro ne peut pas être utilisé pour créer un compte.',
            ]);
        }
    }
}
