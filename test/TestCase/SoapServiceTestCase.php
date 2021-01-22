<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Test\TestCase;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use DeutschePost\Sdk\OneClickForRefund\Serializer\ClassMap;
use DeutschePost\Sdk\OneClickForRefund\Test\TestDouble\SoapClientFake;

abstract class SoapServiceTestCase extends TestCase
{
    /**
     * Mock the SOAP client.
     *
     * Prepare a mock that does not actually perform API calls
     * but returns pre-configured XML responses.
     *
     * @param string[] $responseXml
     *
     * @return \SoapClient|MockObject
     * @throws \ReflectionException
     */
    protected function getSoapClientMock(array $responseXml)
    {
        $clientOptions = [
            'trace'        => true,
            'soap_version' => SOAP_1_1,
            'features'     => SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap'     => ClassMap::get(),
        ];

        /** @var \SoapClient|MockObject $soapClient */
        $soapClient = $this->getMockFromWsdl('', SoapClientFake::class, '', ['__doRequest'], true, $clientOptions);
        $soapClient->expects($this->any())
            ->method('__doRequest')
            ->willReturnOnConsecutiveCalls(...$responseXml);

        return $soapClient;
    }
}
