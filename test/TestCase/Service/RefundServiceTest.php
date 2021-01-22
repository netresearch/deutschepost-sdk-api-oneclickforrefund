<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Test\TestCase\Service;

use DeutschePost\Sdk\OneClickForRefund\Auth\Credentials;
use DeutschePost\Sdk\OneClickForRefund\Auth\TokenStorage;
use DeutschePost\Sdk\OneClickForRefund\Exception\ServiceException;
use DeutschePost\Sdk\OneClickForRefund\Soap\SoapServiceFactory;
use DeutschePost\Sdk\OneClickForRefund\Test\Expectation\CommunicationExpectation;
use DeutschePost\Sdk\OneClickForRefund\Test\Provider\AuthenticationResponseProvider;
use DeutschePost\Sdk\OneClickForRefund\Test\Provider\CancelVouchersResponseProvider;
use DeutschePost\Sdk\OneClickForRefund\Test\TestCase\SoapServiceTestCase;
use Psr\Log\Test\TestLogger;

class RefundServiceTest extends SoapServiceTestCase
{
    /**
     * Scenario: Cancel vouchers at the web service.
     *
     * Assert that
     * - response is of type int
     * - request contains params set during request build
     * - communication gets logged
     *
     * @test
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function success(): void
    {
        $shopOrderId = '1234567890';
        $voucherIds = ['A0031C630F0000000135', 'A0031C630F0000000398'];

        $logger = new TestLogger();
        $credentials = new Credentials(
            'max.mustermann@example.com',
            'portokasse321',
            'PARTNER_ID',
            'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
            1,
            new TokenStorage()
        );

        $responseXml = [
            CancelVouchersResponseProvider::createRetoureIdSuccess(),
            AuthenticationResponseProvider::userAuthSuccess(),
            CancelVouchersResponseProvider::retoureVouchersSuccess()
        ];

        $soapClient = $this->getSoapClientMock($responseXml);
        $serviceFactory = new SoapServiceFactory($soapClient);
        $service = $serviceFactory->createRefundService($credentials, $logger);

        $transactionNumber = $service->cancelVouchers($shopOrderId, $voucherIds);
        self::assertIsInt($transactionNumber);

        $lastRequest = $soapClient->__getLastRequest();
        $lastResponse = $soapClient->__getLastResponse();

        // Assert request data being present in request
        self::assertStringContainsString("<ns1:shopOrderId>{$shopOrderId}</ns1:shopOrderId>", $lastRequest);
        foreach ($voucherIds as $voucherId) {
            $voucherNo = hexdec(substr($voucherId, 10, 9));
            self::assertStringContainsString("<ns1:voucherNo>{$voucherNo}</ns1:voucherNo>", $lastRequest);
        }

        // Assert communication gets logged.
        CommunicationExpectation::assertCommunicationLogged($lastRequest, $lastResponse, $logger);
    }

    /**
     * Scenario: Application credentials are wrong.
     *
     * Assert that
     * - only instances of ServiceException get thrown
     * - communication gets logged with ERROR severity
     *
     * @test
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function appAuthenticationError(): void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessageRegExp('/Unknown channel/');

        $logger = new TestLogger();
        $credentials = new Credentials(
            'max.mustermann@example.com',
            'portokasse321',
            'app',
            'invalid',
            1,
            new TokenStorage()
        );

        $responseXml = AuthenticationResponseProvider::appVerificationFailure();
        $soapClient = $this->getSoapClientMock([$responseXml]);
        $serviceFactory = new SoapServiceFactory($soapClient);
        $service = $serviceFactory->createRefundService($credentials, $logger);

        try {
            $service->cancelVouchers('1234567890');
        } catch (ServiceException $exception) {
            // Assert communication gets logged.
            CommunicationExpectation::assertErrorsLogged(
                $soapClient->__getLastRequest(),
                $soapClient->__getLastResponse(),
                $logger
            );

            throw $exception;
        }
    }

    /**
     * Scenario: User credentials are wrong.
     *
     * Assert that
     * - only instances of ServiceException get thrown
     * - communication gets logged with ERROR severity
     *
     * @test
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function userAuthenticationError(): void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessageRegExp('/invalid password/');

        $logger = new TestLogger();
        $credentials = new Credentials(
            'max.mustermann@example.com',
            'wr0ngPa55',
            'PARTNER_ID',
            'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
            1,
            new TokenStorage()
        );

        $responseXml = [
            CancelVouchersResponseProvider::createRetoureIdSuccess(),
            AuthenticationResponseProvider::userAuthFailure(),
        ];
        $soapClient = $this->getSoapClientMock($responseXml);
        $serviceFactory = new SoapServiceFactory($soapClient);
        $service = $serviceFactory->createRefundService($credentials, $logger);

        try {
            $service->cancelVouchers('1234567890');
        } catch (ServiceException $exception) {
            // Assert communication gets logged.
            CommunicationExpectation::assertErrorsLogged(
                $soapClient->__getLastRequest(),
                $soapClient->__getLastResponse(),
                $logger
            );

            throw $exception;
        }
    }

    /**
     * Scenario: Requested shop order ID is not known, cannot be cancelled.
     *
     * Assert that
     * - only instances of ServiceException get thrown
     * - communication gets logged with ERROR severity
     *
     * @test
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function unknownOrderId(): void
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessageRegExp('/Order not found/');

        $logger = new TestLogger();
        $credentials = new Credentials(
            'max.mustermann@example.com',
            'portokasse321',
            'PARTNER_ID',
            'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
            1,
            new TokenStorage()
        );

        $responseXml = [
            CancelVouchersResponseProvider::createRetoureIdSuccess(),
            AuthenticationResponseProvider::userAuthSuccess(),
            CancelVouchersResponseProvider::unknownOrderIdError(),
        ];

        $soapClient = $this->getSoapClientMock($responseXml);
        $serviceFactory = new SoapServiceFactory($soapClient);
        $service = $serviceFactory->createRefundService($credentials, $logger);

        try {
            $service->cancelVouchers('123XXXX890');
        } catch (ServiceException $exception) {
            // Assert communication gets logged.
            CommunicationExpectation::assertErrorsLogged(
                $soapClient->__getLastRequest(),
                $soapClient->__getLastResponse(),
                $logger
            );

            throw $exception;
        }
    }

    /**
     * Scenario: Invalid or expired token is available in token storage, renewal successful.
     *
     * Assert that new token is stored in token storage.
     *
     * @test
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function tokenRefresh(): void
    {
        $oldToken = 'invalid token';
        $newToken = 'valid token';

        $logger = new TestLogger();
        $tokenStorage = new TokenStorage();
        $tokenStorage->saveToken($oldToken, 3600);
        $credentials = new Credentials(
            'max.mustermann@example.com',
            'portokasse321',
            'PARTNER_ID',
            'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
            1,
            $tokenStorage
        );

        $responseXml = [
            CancelVouchersResponseProvider::createRetoureIdSuccess(),
            CancelVouchersResponseProvider::tokenError(),
            CancelVouchersResponseProvider::createRetoureIdSuccess(),
            AuthenticationResponseProvider::userAuthSuccess($newToken),
            CancelVouchersResponseProvider::retoureVouchersSuccess()
        ];

        $soapClient = $this->getSoapClientMock($responseXml);
        $serviceFactory = new SoapServiceFactory($soapClient);
        $service = $serviceFactory->createRefundService($credentials, $logger);

        $service->cancelVouchers('1234567890');
        self::assertSame($newToken, $tokenStorage->readToken());
    }
}
