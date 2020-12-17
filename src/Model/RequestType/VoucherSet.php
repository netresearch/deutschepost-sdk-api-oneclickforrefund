<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model\RequestType;

class VoucherSet
{
    /**
     * @var string[] $voucherNo
     */
    private $voucherNo;

    /**
     * @param string[] $voucherNo
     */
    public function __construct(array $voucherNo)
    {
        $this->voucherNo = $voucherNo;
    }
}
