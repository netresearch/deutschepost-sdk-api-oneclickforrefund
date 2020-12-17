<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model\RequestType;

class ShoppingCart
{
    /**
     * @var string $shopOrderId
     */
    private $shopOrderId;

    /**
     * @var VoucherSet $voucherSet
     */
    private $voucherSet;

    public function __construct(string $shopOrderId)
    {
        $this->shopOrderId = $shopOrderId;
    }

    public function setVoucherSet(VoucherSet $voucherSet): void
    {
        $this->voucherSet = $voucherSet;
    }
}
