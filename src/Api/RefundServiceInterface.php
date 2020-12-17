<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Api;

/**
 * @api
 */
interface RefundServiceInterface
{
    /**
     * @param string $orderId
     * @param string[] $voucherIds
     * @return int
     */
    public function cancelVouchers(string $orderId, array $voucherIds = []): int;
}
