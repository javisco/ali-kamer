<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklist extends Model
{
    // Nom de la table explicitement défini
    // car Laravel attendrait "blacklists" par défaut
    protected $table = 'blacklist';

    protected $fillable = [
        'cni_hash',
        'phone_momo',
        'phone_number',
        'ip_address',
        'reason',
        'created_by',
    ];

    // ── Relations ─────────────────────────────────────────────────────

    // Admin qui a créé cette entrée de blacklist
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Méthodes statiques de vérification ────────────────────────────

    // Vérifie si un numéro de téléphone est blacklisté
    // Utilisé dans AuthService avant toute inscription
    public static function isPhoneBlacklisted(string $phone): bool
    {
        return self::where('phone_number', $phone)
            ->orWhere('phone_momo', $phone)
            ->exists();
    }

    // Vérifie si une CNI est blacklistée via son hash SHA-256
    // La CNI en clair n'est JAMAIS stockée — on compare uniquement les hashs
    public static function isCniBlacklisted(string $cniNumber): bool
    {
        // Hasher la CNI soumise pour comparer avec ce qui est en base
        $hash = hash('sha256', $cniNumber);

        return self::where('cni_hash', $hash)->exists();
    }

    // Vérifie si une adresse IP est blacklistée
    // Protection contre les tentatives multiples depuis la même IP
    public static function isIpBlacklisted(string $ip): bool
    {
        return self::where('ip_address', $ip)->exists();
    }

    // ── Helper pour blacklister une CNI ──────────────────────────────

    // Reçoit la CNI en clair, la hashe et stocke uniquement le hash
    // Appelé depuis KycService::blacklist()
    public static function addCni(
        string $cniNumber,
        string $reason,
        int $createdBy
    ): self {
        return self::create([
            'cni_hash'   => hash('sha256', $cniNumber),
            'reason'     => $reason,
            'created_by' => $createdBy,
        ]);
    }
}
