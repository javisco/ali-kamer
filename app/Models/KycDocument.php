<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    protected $fillable = ['user_id', 'cni_front_url', 'cni_back_url', 'selfie_url', 'rccm_url'];



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
}
