<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Serializer;

use DeutschePost\Sdk\OneClickForRefund\Model;

class ClassMap
{
    /**
     * Map WSDL types to PHP classes.
     *
     * @return string[]
     */
    public static function get(): array
    {
        return [
            // request types
            'AuthenticateUserRequestType' => Model\AuthenticateUserRequest::class,
            'RetoureVouchersRequestType' => Model\CancelVouchersRequest::class,
            'ShoppingCartType' => Model\RequestType\ShoppingCart::class,
            'VoucherSetType' => Model\RequestType\VoucherSet::class,

            // response types
            'AuthenticateUserResponseType' => Model\AuthenticateUserResponse::class,
            'CreateRetoureIdResponse' => Model\CreateRetoureIdResponse::class,
            'RetoureVouchersResponseType' => Model\CancelVouchersResponse::class,
        ];
    }
}
