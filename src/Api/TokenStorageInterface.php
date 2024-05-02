<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Api;

use DeutschePost\Sdk\OneClickForRefund\Exception\AuthenticationStorageException;

/**
 * @api
 */
interface TokenStorageInterface
{
    /**
     * @throws AuthenticationStorageException
     */
    public function readToken(): string;

    /**
     * @param string $token Authorization token
     * @param int $lifetime Expiry time in seconds
     * @throws AuthenticationStorageException
     */
    public function saveToken(string $token, int $lifetime): void;
}
