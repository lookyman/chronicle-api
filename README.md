# Lookyman/Chronicle

Client library for interacting with [Chronicle](https://github.com/paragonie/chronicle).

[![Build Status](https://travis-ci.org/lookyman/chronicle-api.svg?branch=master)](https://travis-ci.org/lookyman/chronicle-api)
[![Coverage Status](https://coveralls.io/repos/github/lookyman/chronicle-api/badge.svg?branch=master)](https://coveralls.io/github/lookyman/chronicle-api?branch=master)
[![Downloads](https://img.shields.io/packagist/dt/lookyman/chronicle-api.svg)](https://packagist.org/packages/lookyman/chronicle-api)
[![Latest stable](https://img.shields.io/packagist/v/lookyman/chronicle-api.svg)](https://packagist.org/packages/lookyman/chronicle-api)
[![PHPStan level](https://img.shields.io/badge/PHPStan-7-brightgreen.svg)](https://img.shields.io/badge/PHPStan-7-brightgreen.svg)

```php
use Lookyman\Chronicle\Api;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;

$api = new Api(
	new Sapient(new Adapter()), // Adapter must implement ParagonIE\Sapient\Adapter\ConvenienceInterface 
	new Client(), // Client must implement Http\Client\HttpClient
	new SigningSecretKey(Base64UrlSafe::decode('your signing key')),
	new SigningPublicKey(Base64UrlSafe::decode('chronicle public key')),
	'https://chronicle-uri.com',
	'your client id' // optional
);

$api->publish('hello world'); // this won't work if you don't set your client id
$api->lastHash(); // this will
```
