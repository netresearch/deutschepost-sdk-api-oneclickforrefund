# Deutsche Post INTERNETMARKE 1C4R API SDK

The DP OneClickForRefund API SDK package offers an interface to the following web services:

- OneClickForRefund

## Requirements

### System Requirements

- PHP 7.1+ with SOAP extension

### Package Requirements

- `psr/log`: PSR-3 logger interfaces

### Development Package Requirements

- `phpunit/phpunit`: Testing framework

## Installation

```bash
$ composer require deutschepost/sdk-api-oneclickforrefund
```

## Uninstallation

```bash
$ composer remove deutschepost/sdk-api-oneclickforrefund
```

## Testing

```bash
$ ./vendor/bin/phpunit -c test/phpunit.xml
```

## Features

The DP OneClickForRefund API SDK supports the following features:

* Cancel vouchers

The web service requires an authentication token.  The library
retrieves a token but discards it after the process terminates.
In order to reuse the token, a persistent storage can be passed in
([Persist Authentication Token](#persist-authentication-token)).

### Cancel Vouchers

Submit a return for INTERNETMARKE stamps.

#### Public API

The library's components suitable for consumption comprise

* services:
    * service factory
    * refund service
* data transfer objects:
    * credentials

#### Usage

```php
$shopOrderId = '1234567890';
$voucherIds = ['A0031C630F0000000135', 'A0031C630F0000000398'];

$logger = new \Psr\Log\Test\TestLogger();
$tokenStorage = new \DeutschePost\Sdk\OneClickForRefund\Auth\TokenStorage();
$credentials = new \DeutschePost\Sdk\OneClickForRefund\Auth\Credentials(
    $username = 'max.mustermann@example.com',
    $password = 'portokasse321',
    $partnerId = 'PARTNER_ID',
    $partnerKey = 'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
    $keyPhase = 1,
    $tokenStorage
);

$serviceFactory = new \DeutschePost\Sdk\OneClickForRefund\Service\ServiceFactory();
$service = $serviceFactory->createRefundService($credentials, $logger);

// cancel all the original order's vouchers OR
$service->cancelVouchers($shopOrderId);

// cancel some of the original order's vouchers
$service->cancelVouchers($shopOrderId, $voucherIds);
```

### Persist Authentication Token

To reuse a token during its lifetime, the credentials object can be created with
a custom token storage. Implement access to a database, cache, or any other
suitable source.

#### Usage

```php
// PersistentTokenStorage implements \DeutschePost\Sdk\OneClickForRefund\Api\TokenStorageInterface
$tokenStorage = new \My\OneClickForRefund\Auth\PersistentTokenStorage();
$credentials = new \DeutschePost\Sdk\OneClickForRefund\Auth\Credentials(
    $username = 'max.mustermann@example.com',
    $password = 'portokasse321',
    $partnerId = 'PARTNER_ID',
    $partnerKey = 'SCHLUESSEL_DPWN_MEINMARKTPLATZ',
    $keyPhase = 1,
    $tokenStorage
);
```
