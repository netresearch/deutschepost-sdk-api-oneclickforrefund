<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model;

use DeutschePost\Sdk\OneClickForRefund\Model\RequestType\ShoppingCart;

class CancelVouchersRequest
{
    public function __construct(
        private string $userToken,
        private string $shopRetoureId,
        private ShoppingCart $shoppingCart
    ) {
    }
}
