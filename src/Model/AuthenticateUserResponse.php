<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model;

class AuthenticateUserResponse
{
    /**
     * @var string $userToken
     */
    private $userToken;

    public function getUserToken(): string
    {
        return $this->userToken;
    }
}
