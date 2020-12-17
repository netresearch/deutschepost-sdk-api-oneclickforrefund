<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Api;

use DeutschePost\Sdk\OneClickForRefund\Exception\ServiceException;

/**
 * @api
 */
interface RefundServiceInterface
{
    /**
     * Request a refund for INTERNETMARKE vouchers.
     *
     * Multiple INTERNETMARKE vouchers can be created with one order.
     * To cancel all the order's vouchers, the second argument can be omitted.
     *
     * @param string $orderId Original shopping cart ID to refund.
     * @param string[] $voucherIds Subset of vouchers to refund (optional).
     * @return int Transaction number under which the refund has been posted in the Deutsche Post system.
     *
     * @throws ServiceException
     */
    public function cancelVouchers(string $orderId, array $voucherIds = []): int;
}
