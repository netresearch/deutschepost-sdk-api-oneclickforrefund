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
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractDecorator;

class ErrorHandlerDecorator extends AbstractDecorator
{
    /**
     * Determine if the SOAP fault expresses an authentication error.
     *
     * The RetoureVoucherException wraps different types of exceptions.
     * To find out if the actual exception is related to a an invalid token,
     * the error ID property gets examined.
     */
    private function isAuthError(\stdClass $retoureVoucherException): bool
    {
        return (
            property_exists($retoureVoucherException, 'errors')
            && property_exists($retoureVoucherException->errors, 'id')
            && $retoureVoucherException->errors->id === 'unknownUser'
        );
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        try {
            return parent::retoureVouchers($requestType);
        } catch (\SoapFault $fault) {
            if (
                property_exists($fault, 'detail')
                && $fault->detail !== null
                && property_exists($fault->detail, 'RetoureVoucherException')
            ) {
                if ($this->isAuthError($fault->detail->RetoureVoucherException)) {
                    throw new AuthenticationErrorException(sprintf('%s ', $fault->getMessage()));
                }

                throw new DetailedErrorException(sprintf('%s ', $fault->getMessage()));
            }

            throw $fault;
        }
    }
}
