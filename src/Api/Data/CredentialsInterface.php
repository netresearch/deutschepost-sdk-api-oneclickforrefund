<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Api\Data;

use DeutschePost\Sdk\OneClickForRefund\Api\TokenStorageInterface;

/**
 * @api
 */
interface CredentialsInterface
{
    /**
     * Obtain the email address with which the user is registered.
     */
    public function getUsername(): string;

    /**
     * Obtain the password for the user account.
     */
    public function getPassword(): string;

    /**
     * Obtain the partner's application ID provided by Deutsche Post.
     */
    public function getPartnerId(): string;

    /**
     * Obtain the partner's application key (`SCHLUESSEL_DPWN_MEINMARKTPLATZ`) provided by Deutsche Post.
     */
    public function getPartnerKey(): string;

    /**
     * Obtain the agreed version of the secret.
     */
    public function getKeyPhase(): int;

    /**
     * Obtain the authorization token provider.
     */
    public function getTokenStorage(): TokenStorageInterface;
}
