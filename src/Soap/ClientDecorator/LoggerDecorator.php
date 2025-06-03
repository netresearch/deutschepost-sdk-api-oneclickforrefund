<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator;

use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CreateRetoureIdResponse;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractClient;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractDecorator;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggerDecorator extends AbstractDecorator
{
    private \SoapClient $soapClient;

    private \Psr\Log\LoggerInterface $logger;

    public function __construct(AbstractClient $client, \SoapClient $soapClient, LoggerInterface $logger)
    {
        $this->soapClient = $soapClient;
        $this->logger = $logger;

        parent::__construct($client);
    }

    /**
     * @throws \Exception
     */
    private function logCommunication(\Closure $performRequest): mixed
    {
        $logLevel = LogLevel::INFO;

        try {
            return $performRequest();
        } catch (\Exception $exception) {
            $logLevel = LogLevel::ERROR;
            throw $exception;
        } finally {
            $lastRequest = sprintf(
                "%s\n%s",
                $this->soapClient->__getLastRequestHeaders(),
                $this->soapClient->__getLastRequest()
            );

            $lastResponse = sprintf(
                "%s\n%s",
                $this->soapClient->__getLastResponseHeaders(),
                $this->soapClient->__getLastResponse()
            );

            $this->logger->log($logLevel, $lastRequest);
            $this->logger->log($logLevel, $lastResponse);

            if (isset($exception)) {
                $this->logger->log($logLevel, $exception->getMessage());
            }
        }
    }

    public function authenticateUser(AuthenticateUserRequest $requestType): AuthenticateUserResponse
    {
        $performRequest = (fn(): AuthenticateUserResponse => parent::authenticateUser($requestType));

        return $this->logCommunication($performRequest);
    }

    public function createRetoureId(): CreateRetoureIdResponse
    {
        $performRequest = (fn(): CreateRetoureIdResponse => parent::createRetoureId());

        return $this->logCommunication($performRequest);
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        $performRequest = (fn(): CancelVouchersResponse => parent::retoureVouchers($requestType));

        return $this->logCommunication($performRequest);
    }
}
