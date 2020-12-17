<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model;

use DeutschePost\Sdk\OneClickForRefund\Model\RequestType\ShoppingCart;

class CancelVouchersRequest
{
    /**
     * @var string $userToken
     */
    private $userToken;

    /**
     * @var string $shopRetoureId
     */
    private $shopRetoureId;

    /**
     * @var ShoppingCart $shoppingCart
     */
    private $shoppingCart;

    public function __construct(string $userToken, string $shopRetoureId, ShoppingCart $shoppingCart)
    {
        $this->userToken = $userToken;
        $this->shopRetoureId = $shopRetoureId;
        $this->shoppingCart = $shoppingCart;
    }
}
