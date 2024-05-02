<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model\RequestType;

class ShoppingCart
{
    private ?VoucherSet $voucherSet = null;

    public function __construct(private string $shopOrderId)
    {
    }

    public function setVoucherSet(VoucherSet $voucherSet): void
    {
        $this->voucherSet = $voucherSet;
    }
}
