<?php
declare(strict_types=1);

namespace Lookyman\Chronicle;

use Http\Client\HttpAsyncClient;
use Http\Promise\FulfilledPromise;
use Interop\Http\Factory\RequestFactoryInterface;
use PHPUnit\Framework\TestCase;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Lookyman\Chronicle\Api
 * @covers \Lookyman\Chronicle\AbstractApi
 */
final class ApiTest extends TestCase
{

	public function testLastHash(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/lasthash'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->lastHash()->wait());
	}

	public function testLookup(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/lookup/foo'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->lookup('foo')->wait());
	}

	public function testSince(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/since/foo'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->since('foo')->wait());
	}

	public function testExport(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/export'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->export()->wait());
	}

	public function testIndex(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->index()->wait());
	}

	public function testRegister(): void
	{
		$requestStream = $this->createMock(StreamInterface::class);
		$requestStream->expects(self::once())->method('__toString')
			->willReturn('{"publickey":"aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q=","comment":"foo"}');

		$responseStream = $this->createMock(StreamInterface::class);
		$responseStream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::at(0))->method('withBody')->willReturn($request);
		$request->expects(self::at(1))->method('withHeader')->with(
			'Content-Type',
			'application/json'
		)->willReturn($request);
		$request->expects(self::at(2))->method('withHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'client'
		)->willReturn($request);
		$request->expects(self::at(3))->method('getBody')->willReturn($requestStream);
		$request->expects(self::at(4))->method('withHeader')->with(
			Sapient::HEADER_SIGNATURE_NAME,
			'iGV5WcBp7A3eHFeb2OeM9n1i0dPOC5_DsnAvl7p29XUWWjvqSJ827v3Gw8zM8H4hvfyEWAlf8CZ0wvaUpdtZDA=='
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($responseStream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'POST',
			'uri/chronicle/register'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);
		$api->authenticate(
			new SigningSecretKey(Base64UrlSafe::decode('v4hyJ3AHsLUVVcqpBjtnNZ98CazBqrnIZ-Ek5mnTMo4PdTH7Yv-B8ZBgruHUi2jq_7CC74XHJE-0c0LCMDTmwQ==')),
			'client'
		);

		self::assertEquals(['result'], $api->register(
			new SigningPublicKey(Base64UrlSafe::decode('aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q=')),
			'foo'
		)->wait());
	}

	public function testRevoke(): void
	{
		$requestStream = $this->createMock(StreamInterface::class);
		$requestStream->expects(self::once())->method('__toString')
			->willReturn('{"clientid":"foo","publickey":"aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q="}');

		$responseStream = $this->createMock(StreamInterface::class);
		$responseStream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::at(0))->method('withBody')->willReturn($request);
		$request->expects(self::at(1))->method('withHeader')->with(
			'Content-Type',
			'application/json'
		)->willReturn($request);
		$request->expects(self::at(2))->method('withHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'client'
		)->willReturn($request);
		$request->expects(self::at(3))->method('getBody')->willReturn($requestStream);
		$request->expects(self::at(4))->method('withHeader')->with(
			Sapient::HEADER_SIGNATURE_NAME,
			'q0ILXTcSkyl75zsJgh_6fnGGiRrQpnz8QkQNjfNY6LaDPbCr4mXnWs33KY-lqMPH-9qg9pybHxr3WszznrjmCw=='
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($responseStream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'POST',
			'uri/chronicle/revoke'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);
		$api->authenticate(
			new SigningSecretKey(Base64UrlSafe::decode('v4hyJ3AHsLUVVcqpBjtnNZ98CazBqrnIZ-Ek5mnTMo4PdTH7Yv-B8ZBgruHUi2jq_7CC74XHJE-0c0LCMDTmwQ==')),
			'client'
		);

		self::assertEquals(['result'], $api->revoke(
			'foo',
			new SigningPublicKey(Base64UrlSafe::decode('aAtpZ1BH8GbmKbXx7IN7_pTN9fM9WwGiZmKUajsLi6Q='))
		)->wait());
	}

	public function testPublish(): void
	{
		$requestStream = $this->createMock(StreamInterface::class);
		$requestStream->expects(self::once())->method('__toString')
			->willReturn('foo');

		$responseStream = $this->createMock(StreamInterface::class);
		$responseStream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::at(0))->method('withBody')->willReturn($request);
		$request->expects(self::at(1))->method('withHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'client'
		)->willReturn($request);
		$request->expects(self::at(2))->method('getBody')->willReturn($requestStream);
		$request->expects(self::at(3))->method('withHeader')->with(
			Sapient::HEADER_SIGNATURE_NAME,
			'O42hyULuTzw9atrnPjH4P4ePPgZHxDF0TLG3Co3xj0f7QPLharhEWRAVo7mHwqpNcaaOTh7LK2FnL9rCL1iLDA=='
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($responseStream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'POST',
			'uri/chronicle/publish'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);
		$api->authenticate(
			new SigningSecretKey(Base64UrlSafe::decode('v4hyJ3AHsLUVVcqpBjtnNZ98CazBqrnIZ-Ek5mnTMo4PdTH7Yv-B8ZBgruHUi2jq_7CC74XHJE-0c0LCMDTmwQ==')),
			'client'
		);

		self::assertEquals(['result'], $api->publish('foo')->wait());
	}

	public function testReplica(): void
	{
		$client = $this->createMock(HttpAsyncClient::class);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);

		$publicKey = $this->createMock(SigningPublicKey::class);

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		$replica = $api->replica('aaa');

		$reflectionPropertyClient = new \ReflectionProperty(Replica::class, 'client');
		$reflectionPropertyClient->setAccessible(\true);
		self::assertSame($client, $reflectionPropertyClient->getValue($replica));

		$reflectionPropertyRequestFactory = new \ReflectionProperty(Replica::class, 'requestFactory');
		$reflectionPropertyRequestFactory->setAccessible(\true);
		self::assertSame($requestFactory, $reflectionPropertyRequestFactory->getValue($replica));

		$reflectionPropertyChronicleUri = new \ReflectionProperty(Replica::class, 'chronicleUri');
		$reflectionPropertyChronicleUri->setAccessible(\true);
		self::assertEquals('uri', $reflectionPropertyChronicleUri->getValue($replica));

		$reflectionPropertySource = new \ReflectionProperty(Replica::class, 'source');
		$reflectionPropertySource->setAccessible(\true);
		self::assertEquals('aaa', $reflectionPropertySource->getValue($replica));

		$reflectionPropertyChroniclePublicKey = new \ReflectionProperty(AbstractApi::class, 'chroniclePublicKey');
		$reflectionPropertyChroniclePublicKey->setAccessible(\true);
		self::assertSame($publicKey, $reflectionPropertyChroniclePublicKey->getValue($replica));
	}

	public function testReplicas(): void
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$promise = new FulfilledPromise($response);

		$client = $this->createMock(HttpAsyncClient::class);
		$client->expects(self::once())->method('sendAsyncRequest')->with($request)->willReturn($promise);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica'
		)->willReturn($request);

		$publicKey = $this->createMock(SigningPublicKey::class);
		$publicKey->expects(self::once())->method('getString')->with(\true)
			->willReturn(Base64UrlSafe::decode('uW197cTmhf0MGDZU-NtWr1bsQ-MxSCzFa64mbjjl4MQ='));

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		self::assertEquals(['result'], $api->replicas()->wait());
	}

}
