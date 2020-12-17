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
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $partnerId;

    /**
     * @var string
     */
    private $partnerKey;

    /**
     * @var int
     */
    private $keyPhase;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param string $username
     * @param string $password
     * @param string $partnerId
     * @param string $partnerKey
     * @param int $keyPhase
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        string $username,
        string $password,
        string $partnerId,
        string $partnerKey,
        int $keyPhase,
        TokenStorageInterface $tokenStorage
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->partnerId = $partnerId;
        $this->partnerKey = $partnerKey;
        $this->keyPhase = $keyPhase;
        $this->tokenStorage = $tokenStorage;
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
