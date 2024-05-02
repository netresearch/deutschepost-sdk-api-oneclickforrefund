<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Auth;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\TokenStorageInterface;

class Credentials implements CredentialsInterface
{
    public function __construct(
        private string $username,
        private string $password,
        private string $partnerId,
        private string $partnerKey,
        private int $keyPhase,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    public function getPartnerKey(): string
    {
        return $this->partnerKey;
    }

    public function getKeyPhase(): int
    {
        return $this->keyPhase;
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }
}
