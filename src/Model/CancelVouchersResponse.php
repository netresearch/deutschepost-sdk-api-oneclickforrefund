<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model;

class CancelVouchersResponse
{
    /**
     * @var int $retoureTransactionId
     */
    private $retoureTransactionId;

    public function getRetoureTransactionId(): int
    {
        return $this->retoureTransactionId;
    }
}
