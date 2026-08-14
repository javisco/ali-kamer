<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id', 'action', 'target_type',
        'target_id', 'note', 'ip_address',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Enregistrer une action admin facilement
    public static function record(
        User $admin,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $note = null
    ): void {
        self::create([
            'admin_id'    => $admin->id,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'note'        => $note,
            'ip_address'  => request()->ip(),
        ]);
    }
}