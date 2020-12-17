<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator;

use DeutschePost\Sdk\OneClickForRefund\Exception\AuthenticationErrorException;
use DeutschePost\Sdk\OneClickForRefund\Exception\DetailedErrorException;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CreateRetoureIdResponse;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractDecorator;

class ErrorHandlerDecorator extends AbstractDecorator
{
    public function createRetoureId(): CreateRetoureIdResponse
    {
        try {
            return parent::createRetoureId();
        } catch (\SoapFault $fault) {
            if (isset($fault->detail) && property_exists($fault->detail, 'AuthenticateUserException')) {
                throw new AuthenticationErrorException($fault->getMessage());
            }

            throw $fault;
        }
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        try {
            return parent::retoureVouchers($requestType);
        } catch (\SoapFault $fault) {
            if (isset($fault->detail) && property_exists($fault->detail, 'AuthenticateUserException')) {
                throw new AuthenticationErrorException($fault->getMessage());
            }

            if (isset($fault->detail) && property_exists($fault->detail, 'RetoureVoucherException')) {
                throw new DetailedErrorException(sprintf('%s ', $fault->getMessage()));
            }

            throw $fault;
        }
    }
}
