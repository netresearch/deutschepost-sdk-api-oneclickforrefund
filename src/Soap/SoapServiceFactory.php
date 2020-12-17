<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\RefundServiceInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\ServiceFactoryInterface;
use Psr\Log\LoggerInterface;

class SoapServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var \SoapClient
     */
    private $soapClient;

    public function __construct(\SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    public function createRefundService(
        CredentialsInterface $credentials,
        LoggerInterface $logger
    ): RefundServiceInterface {
        // TODO: Implement createRefundService() method.
    }
}
