<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Auth;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Exception\AuthenticationStorageException;
use DeutschePost\Sdk\OneClickForRefund\Exception\ServiceException;
use DeutschePost\Sdk\OneClickForRefund\Service\AuthenticationService;

class TokenProvider
{
    /**
     * @var CredentialsInterface
     */
    private $credentials;

    /**
     * @var AuthenticationService
     */
    private $authService;

    public function __construct(CredentialsInterface $credentials, AuthenticationService $authService)
    {
        $this->credentials = $credentials;
        $this->authService = $authService;
    }

    /**
     * Load token from storage if available, from authentication endpoint otherwise.
     *
     * @return string
     * @throws ServiceException
     */
    public function getToken(): string
    {
        try {
            $token = $this->credentials->getTokenStorage()->readToken();
        } catch (AuthenticationStorageException $exception) {
            $token = '';
        }

        if (!$token) {
            $token = $this->authService->authenticate(
                $this->credentials->getUsername(),
                $this->credentials->getPassword()
            );

            // API documentation denotes token as valid for one hour.
            $this->credentials->getTokenStorage()->saveToken($token, 3600);
        }

        return $token;
    }

    /**
     * Clear token (enforce loading it from authentication endpoint on next attempt).
     *
     * @throws AuthenticationStorageException
     */
    public function resetToken(): void
    {
        $this->credentials->getTokenStorage()->saveToken('', 0);
    }
}
