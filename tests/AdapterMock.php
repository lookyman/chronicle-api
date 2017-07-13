<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use ParagonIE\Sapient\Adapter\AdapterInterface;
use ParagonIE\Sapient\Adapter\ConvenienceInterface;
use ParagonIE\Sapient\CryptographyKeys\SealingPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SharedAuthenticationKey;
use ParagonIE\Sapient\CryptographyKeys\SharedEncryptionKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @phpcsSuppress LookymanCodingStandard.Classes.FinalClass.NotFinalClass
 */
class AdapterMock implements AdapterInterface, ConvenienceInterface
{

	public function stringToStream(string $input): StreamInterface
	{
	}

	public function createSymmetricAuthenticatedJsonRequest(
		string $method,
		string $uri,
		array $arrayToJsonify,
		SharedAuthenticationKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSymmetricAuthenticatedJsonResponse(
		int $status,
		array $arrayToJsonify,
		SharedAuthenticationKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSymmetricEncryptedJsonRequest(
		string $method,
		string $uri,
		array $arrayToJsonify,
		SharedEncryptionKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSymmetricEncryptedJsonResponse(
		int $status,
		array $arrayToJsonify,
		SharedEncryptionKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSealedJsonRequest(
		string $method,
		string $uri,
		array $arrayToJsonify,
		SealingPublicKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSealedJsonResponse(
		int $status,
		array $arrayToJsonify,
		SealingPublicKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSignedJsonRequest(
		string $method,
		string $uri,
		array $arrayToJsonify,
		SigningSecretKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSignedJsonResponse(
		int $status,
		array $arrayToJsonify,
		SigningSecretKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSymmetricAuthenticatedRequest(
		string $method,
		string $uri,
		string $body,
		SharedAuthenticationKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSymmetricAuthenticatedResponse(
		int $status,
		string $body,
		SharedAuthenticationKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSymmetricEncryptedRequest(
		string $method,
		string $uri,
		string $body,
		SharedEncryptionKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSymmetricEncryptedResponse(
		int $status,
		string $body,
		SharedEncryptionKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSealedRequest(
		string $method,
		string $uri,
		string $body,
		SealingPublicKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSealedResponse(
		int $status,
		string $body,
		SealingPublicKey $key,
		array $headers = [],
		string $version = '1.1'
	): ResponseInterface {
	}

	public function createSignedRequest(
		string $method,
		string $uri,
		string $body,
		SigningSecretKey $key,
		array $headers = []
	): RequestInterface {
	}

	public function createSignedResponse(
		int $status,
		string $body,
		SigningSecretKey $key,
		array $headers = [],
		string $version = '1.1'
	) {
	}

}
