<?php

namespace App\Services;

class IdentityResolution
{
    public function __construct(
        public readonly string $decision,
        public readonly ?string $reason,
        public readonly array $matchedIdentifiers,
        public readonly array $linkedAccounts,
        public readonly int $highestConfidence,
    ) {
    }

    public function isAllowed(): bool
    {
        return $this->decision === 'allow';
    }

    public function isBlocked(): bool
    {
        return $this->decision === 'block';
    }

    public function isRestricted(): bool
    {
        return $this->decision === 'restrict';
    }

    public function isChallenged(): bool
    {
        return $this->decision === 'challenge';
    }
}
