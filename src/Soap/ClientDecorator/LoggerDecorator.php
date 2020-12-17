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
    /**
     * @var \SoapClient
     */
    private $soapClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(AbstractClient $client, \SoapClient $soapClient, LoggerInterface $logger)
    {
        $this->soapClient = $soapClient;
        $this->logger = $logger;

        parent::__construct($client);
    }

    /**
     * @param \Closure $performRequest
     * @return mixed
     * @throws \Exception
     */
    private function logCommunication(\Closure $performRequest)
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
        $performRequest = function () use ($requestType) {
            return parent::authenticateUser($requestType);
        };

        return $this->logCommunication($performRequest);
    }

    public function createRetoureId(): CreateRetoureIdResponse
    {
        $performRequest = function () {
            return parent::createRetoureId();
        };

        return $this->logCommunication($performRequest);
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        $performRequest = function () use ($requestType) {
            return parent::retoureVouchers($requestType);
        };

        return $this->logCommunication($performRequest);
    }
}
