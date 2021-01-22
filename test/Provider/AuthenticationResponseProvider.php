<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Test\Provider;

class AuthenticationResponseProvider
{
    /**
     * Application verification fails, wrong partner ID or key.
     *
     * @return string
     */
    public static function appVerificationFailure(): string
    {
        return \file_get_contents(__DIR__ . '/_files/auth/appVerificationFault.xml');
    }

    /**
     * User authentication fails, wrong user name or invalid password.
     *
     * @return string
     */
    public static function userAuthFailure(): string
    {
        return \file_get_contents(__DIR__ . '/_files/auth/authenticateUserFault.xml');
    }

    /**
     * User authentication succeeds, token included with response.
     *
     * @param string $newToken
     * @return string
     */
    public static function userAuthSuccess(string $newToken = 'myToken'): string
    {
        $tokenResponse = \file_get_contents(__DIR__ . '/_files/auth/authenticateUserResponse.xml');
        return str_replace('%userToken%', $newToken, $tokenResponse);
    }
}
