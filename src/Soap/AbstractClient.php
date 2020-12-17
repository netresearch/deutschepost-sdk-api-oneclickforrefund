<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap;

use DeutschePost\Sdk\OneClickForRefund\Exception\AuthenticationErrorException;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CreateRetoureIdResponse;

abstract class AbstractClient
{
    /**
     * Action to authenticate a user on the system.
     *
     * @param AuthenticateUserRequest $requestType
     * @return AuthenticateUserResponse
     *
     * @throws \SoapFault
     * @throws \Exception
     */
    abstract public function authenticateUser(AuthenticateUserRequest $requestType): AuthenticateUserResponse;

    /**
     * The service operation returns a new return ID that can be used to generate a return.
     *
     * @return CreateRetoureIdResponse
     *
     * @throws AuthenticationErrorException
     * @throws \SoapFault
     * @throws \Exception
     */
    abstract public function createRetoureId(): CreateRetoureIdResponse;

    /**
     * The service operation is used to submit returns for postage stamps.
     *
     * @param CancelVouchersRequest $requestType
     * @return CancelVouchersResponse
     *
     * @throws AuthenticationErrorException
     * @throws \SoapFault
     * @throws \Exception
     */
    abstract public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse;
}
