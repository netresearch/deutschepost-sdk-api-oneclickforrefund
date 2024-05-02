<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace DeutschePost\Sdk\OneClickForRefund\Soap\ClientDecorator;

use DeutschePost\Sdk\OneClickForRefund\Api\Data\CredentialsInterface;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\AuthenticateUserResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersRequest;
use DeutschePost\Sdk\OneClickForRefund\Model\CancelVouchersResponse;
use DeutschePost\Sdk\OneClickForRefund\Model\CreateRetoureIdResponse;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractClient;
use DeutschePost\Sdk\OneClickForRefund\Soap\AbstractDecorator;

class AuthenticationDecorator extends AbstractDecorator
{
    public function __construct(
        AbstractClient $client,
        private readonly \SoapClient $soapClient,
        private readonly CredentialsInterface $credentials
    ) {
        parent::__construct($client);
    }

    private function setAuthHeaders(): void
    {
        $partnerId = $this->credentials->getPartnerId();
        $keyPhase = $this->credentials->getKeyPhase();
        $key = $this->credentials->getPartnerKey();

        // The request timestamp used for authentication must be given in CE(S)T!
        $timezoneCet = new \DateTimeZone('Europe/Berlin');
        $timeCet     = new \DateTime('now', $timezoneCet);
        $date        = $timeCet->format("dmY-His");

        $signature = hash('md5', "$partnerId::$date::$keyPhase::$key");
        $signature = substr($signature, 0, 8);

        $ns = 'http://oneclickforrefund.dpag.de';
        $headers = [
            new \SoapHeader($ns, 'PARTNER_ID', new \SOAPVar($partnerId, XSD_STRING)),
            new \SoapHeader($ns, 'PARTNER_SIGNATURE', new \SOAPVar($signature, XSD_STRING)),
            new \SoapHeader($ns, 'REQUEST_TIMESTAMP', new \SOAPVar($date, XSD_STRING)),
            new \SoapHeader($ns, 'KEY_PHASE', new \SOAPVar($keyPhase, XSD_STRING)),
        ];

        $this->soapClient->__setSoapHeaders($headers);
    }

    public function authenticateUser(AuthenticateUserRequest $requestType): AuthenticateUserResponse
    {
        $this->setAuthHeaders();
        return parent::authenticateUser($requestType);
    }

    public function createRetoureId(): CreateRetoureIdResponse
    {
        $this->setAuthHeaders();
        return parent::createRetoureId();
    }

    public function retoureVouchers(CancelVouchersRequest $requestType): CancelVouchersResponse
    {
        $this->setAuthHeaders();
        return parent::retoureVouchers($requestType);
    }
}
