<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'cni_front_url',
        'cni_back_url',
        'selfie_url',
        'rccm_url',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewer_id',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }
}
