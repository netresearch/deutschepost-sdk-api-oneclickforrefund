<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Api;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use Psr\Log\LoggerInterface;

/**
 * @api
 */
interface ServiceFactoryInterface
{
    /**
     * Create the service instance to process voucher cancellation.
     *
     * @throws \RuntimeException
     */
    public function createRefundService(
        CredentialsInterface $credentials,
        LoggerInterface $logger
    ): RefundServiceInterface;
}
