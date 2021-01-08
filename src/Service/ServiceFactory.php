<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Service;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\RefundServiceInterface;
use DeutschePost\Sdk\OneClickForRefund\Api\ServiceFactoryInterface;
use DeutschePost\Sdk\OneClickForRefund\Serializer\ClassMap;
use DeutschePost\Sdk\OneClickForRefund\Soap\SoapServiceFactory;
use Psr\Log\LoggerInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    private const SOAP_WSDL = 'https://internetmarke.deutschepost.de/OneClickForRefund?wsdl';

    public function createRefundService(
        CredentialsInterface $credentials,
        LoggerInterface $logger
    ): RefundServiceInterface {
        $options = [
            'trace' => 1,
            'features' => \SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap' => ClassMap::get(),
        ];

        try {
            $soapClient = new \SoapClient(self::SOAP_WSDL, $options);
        } catch (\SoapFault $soapFault) {
            throw new \RuntimeException($soapFault->getMessage(), $soapFault->getCode(), $soapFault);
        }

        $soapServiceFactory = new SoapServiceFactory($soapClient);
        return $soapServiceFactory->createRefundService($credentials, $logger);
    }
}
