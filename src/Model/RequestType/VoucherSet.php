<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model\RequestType;

class VoucherSet
{
    /**
     * @param string[] $voucherNo
     */
    public function __construct(private array $voucherNo)
    {
    }
}
