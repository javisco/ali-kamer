<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycDocument extends Model
{
    use HasFactory;

    const STATUS_PENDING   = 'pending';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';

    const ADMIN_DECISION_APPROVE   = 'approve';
    const ADMIN_DECISION_REJECT    = 'reject';
    const ADMIN_DECISION_REVIEW    = 'review';
    const ADMIN_DECISION_CHALLENGE = 'challenge';
    const ADMIN_DECISION_RESTRICT  = 'restrict';
    const ADMIN_DECISION_BLOCK     = 'block';

    protected $fillable = [
        'user_id',
        'provider',
        'cni_front_url', 'cni_back_url', 'selfie_url', 'rccm_url',
        'momo_operator', 'momo_number', 'momo_name_check',
        'status', 'rejection_reason', 'reviewed_at', 'reviewer_id',
        'didit_session_id', 'didit_session_number', 'didit_session_status',
        'didit_session_url', 'didit_workflow_id', 'didit_workflow_version',
        'didit_vendor_data', 'didit_environment',
        'document_status', 'ocr_status', 'face_match_status',
        'liveness_status', 'ip_analysis_status', 'identity_resolution_status',
        'decision', 'decision_reason', 'admin_decision', 'admin_decision_reason',
        'liveness_score', 'face_match_score', 'aml_status',
        'cni_hash', 'document_number_masked',
        'full_name', 'date_of_birth', 'nationality', 'expiration_date',
        'name_match_score', 'warnings', 'didit_result', 'didit_media',
        'didit_document_result', 'didit_liveness_result', 'didit_face_match_result',
        'didit_ip_result', 'didit_aml_result', 'didit_review_result',
        'verification_ip',
        'last_webhook_at', 'consent_at', 'consent_ip', 'consent_user_agent',
        'consent_version',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'date_of_birth' => 'date',
            'expiration_date' => 'date',
            'name_match_score' => 'float',
            'liveness_score' => 'float',
            'face_match_score' => 'float',
            'warnings' => 'array',
            'didit_result' => 'array',
            'didit_media' => 'array',
            'didit_document_result' => 'array',
            'didit_liveness_result' => 'array',
            'didit_face_match_result' => 'array',
            'didit_ip_result' => 'array',
            'didit_aml_result' => 'array',
            'didit_review_result' => 'array',
            'last_webhook_at' => 'datetime',
            'consent_at' => 'datetime',
            'momo_name_check' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function decisionLogs()
    {
        return $this->hasMany(DecisionLog::class);
    }

    public function adminReviews()
    {
        return $this->hasMany(AdminReview::class);
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isReviewing(): bool { return $this->status === 'reviewing'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function isDiditApproved(): bool { return $this->didit_session_status === 'Approved'; }
    public function isDiditDeclined(): bool { return $this->didit_session_status === 'Declined'; }
    public function isDiditInReview(): bool { return $this->didit_session_status === 'In Review'; }
    public function hasWarnings(): bool { return ! empty($this->warnings); }
}
