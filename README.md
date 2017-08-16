# Lookyman/Chronicle

Client library for interacting with [Chronicle](https://github.com/paragonie/chronicle).

[![Build Status](https://travis-ci.org/lookyman/chronicle-api.svg?branch=master)](https://travis-ci.org/lookyman/chronicle-api)
[![Coverage Status](https://coveralls.io/repos/github/lookyman/chronicle-api/badge.svg?branch=master)](https://coveralls.io/github/lookyman/chronicle-api?branch=master)
[![Downloads](https://img.shields.io/packagist/dt/lookyman/chronicle-api.svg)](https://packagist.org/packages/lookyman/chronicle-api)
[![Latest stable](https://img.shields.io/packagist/v/lookyman/chronicle-api.svg)](https://packagist.org/packages/lookyman/chronicle-api)
[![PHPStan level](https://img.shields.io/badge/PHPStan-7-brightgreen.svg)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/lookyman/chronicle-api/blob/master/LICENSE)

## Installation

```sh
composer require lookyman/chronicle-api
```

## Usage

```php
use Lookyman\Chronicle\Api;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;

$api = new Api(
	new Client(), // Client must implement Http\Client\HttpAsyncClient
	new RequestFactory(), // RequestFactory must implement Interop\Http\Factory\RequestFactoryInterface
	'https://chronicle.uri',
	new SigningPublicKey(Base64UrlSafe::decode('chronicle public key')) // optional, omit if you don't care about validating API responses
);
var_dump($api->lastHash()->wait());

// you must authenticate first before you can publish a message
$api->authenticate(
	new SigningSecretKey(Base64UrlSafe::decode('your secret key')),
	'your client id'
);
var_dump($api->publish('hello world')->wait());
```

For implementations of client and request factory, you can use for example [Guzzle 6 HTTP Adapter](https://github.com/php-http/guzzle6-adapter) and [HTTP Factory for Guzzle](https://github.com/http-interop/http-factory-guzzle) respectively.

All endpoints return `Http\Promise\Promise`, so you can either just `->wait()` for the response, or handle it asynchronously with `->then()`. Responses are just plain arrays, look up the structure in the Chronicle's documentation.
