<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap;

use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CreateRetoureIdResponse;

/**
 * AbstractDecorator
 *
 * Wrapper around actual soap client to perform the following tasks:
 * - add authentication
 * - transform errors into exceptions
 * - log communication
 */
abstract class AbstractDecorator extends AbstractClient
{
    /**
     * @var AbstractClient
     */
    private $client;

    public function __construct(AbstractClient $client)
    {
        $this->client = $client;
    }

    public function authenticateUser(AuthenticateUserRequest $requestType): AuthenticateUserResponse
    {
        return $this->client->authenticateUser($requestType);
    }

    public function createRetoureId(): CreateRetoureIdResponse
    {
        return $this->client->createRetoureId();
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        return $this->client->retoureVouchers($requestType);
    }
}
