<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Service;

use DeutschePost\Sdk\OneClickForRefund\Api\RefundServiceInterface;
use DeutschePost\Sdk\OneClickForRefund\Auth\TokenProvider;
use DeutschePost\Sdk\OneClickForRefund\Exception\AuthenticationErrorException;
use DeutschePost\Sdk\OneClickForRefund\Exception\ServiceExceptionFactory;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\RequestType\ShoppingCart;
use DeutschePost\Sdk\OneClickForRefund\Model\RequestType\VoucherSet;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractClient;

class RefundService implements RefundServiceInterface
{
    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @var TokenProvider
     */
    private $tokenProvider;

    public function __construct(AbstractClient $client, TokenProvider $tokenProvider)
    {
        $this->client = $client;
        $this->tokenProvider = $tokenProvider;
    }

    public function cancelVouchers(string $orderId, array $voucherIds = []): int
    {
        $voucherNumbers = array_map(
            function (string $voucherId) {
                return (string) hexdec(substr($voucherId, 10, 9));
            },
            $voucherIds
        );

        try {
            $shoppingCart = new ShoppingCart($orderId);
            if (!empty($voucherNumbers)) {
                $shoppingCart->setVoucherSet(new VoucherSet($voucherNumbers));
            }

            $shopRetoureId = $this->client->createRetoureId();
            $request = new CancelVouchersRequest(
                $this->tokenProvider->getToken(),
                $shopRetoureId->getShopRetoureId(),
                $shoppingCart
            );
            $response = $this->client->retoureVouchers($request);
        } catch (AuthenticationErrorException $exception) {
            $this->tokenProvider->resetToken();
            return $this->cancelVouchers($orderId, $voucherIds);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::createServiceException($exception);
        }

        return $response->getRetoureTransactionId();
    }
}
