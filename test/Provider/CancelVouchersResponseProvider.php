<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Test\Provider;

class CancelVouchersResponseProvider
{
    public static function createRetoureIdSuccess(): string
    {
        return \file_get_contents(__DIR__ . '/_files/cancelVoucher/CreateRetoureIdResponse.xml');
    }

    public static function retoureVouchersSuccess(): string
    {
        return \file_get_contents(__DIR__ . '/_files/cancelVoucher/RetoureVouchersResponse.xml');
    }

    public static function tokenError(): string
    {
        return \file_get_contents(__DIR__ . '/_files/cancelVoucher/unknownUserException.xml');
    }

    public static function unknownOrderIdError(): string
    {
        return \file_get_contents(__DIR__ . '/_files/cancelVoucher/unknownShopOrderIdException.xml');
    }
}
