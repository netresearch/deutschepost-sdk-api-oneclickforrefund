<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\RefundServiceInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\ServiceFactoryInterface;
use DeutschePost\Sdk\OneClickForRefund\Auth\TokenProvider;
use DeutschePost\Sdk\OneClickForRefund\Service\AuthenticationService;
use DeutschePost\Sdk\OneClickForRefund\Service\RefundService;
use DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator\AuthenticationDecorator;
use DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator\ErrorHandlerDecorator;
use DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator\LoggerDecorator;
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

    private function createAuthenticationService(
        CredentialsInterface $credentials,
        LoggerInterface $logger
    ): AuthenticationService {
        $pluginClient = new Client($this->soapClient);
        $pluginClient = new AuthenticationDecorator($pluginClient, $this->soapClient, $credentials);
        $pluginClient = new LoggerDecorator($pluginClient, $this->soapClient, $logger);

        return new AuthenticationService($pluginClient);
    }

    public function createRefundService(
        CredentialsInterface $credentials,
        LoggerInterface $logger
    ): RefundServiceInterface {
        $authService = $this->createAuthenticationService($credentials, $logger);
        $tokenProvider = new TokenProvider($credentials, $authService);

        $pluginClient = new Client($this->soapClient);
        $pluginClient = new ErrorHandlerDecorator($pluginClient);
        $pluginClient = new AuthenticationDecorator($pluginClient, $this->soapClient, $credentials);
        $pluginClient = new LoggerDecorator($pluginClient, $this->soapClient, $logger);

        return new RefundService($pluginClient, $tokenProvider);
    }
}
