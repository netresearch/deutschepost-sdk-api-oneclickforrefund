<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Model;

class CreateRetoureIdResponse
{
    /**
     * @var string $shopRetoureId
     */
    private $shopRetoureId;

    public function getShopRetoureId(): string
    {
        return $this->shopRetoureId;
    }
}
