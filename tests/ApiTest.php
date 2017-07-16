<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use PHPUnit\Framework\TestCase;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Lookyman\Chronicle\Api
 */
final class ApiTest extends TestCase
{

	public function testLastHash()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->lastHash());
	}

	public function testLookup()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->lookup('foo'));
	}

	public function testSince()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->since('foo'));
	}

	public function testExport()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->export());
	}

	public function testIndex()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->index());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRegisterUnauthorized()
	{
		$client = $this->createMock(HttpClient::class);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);

		$api = new Api(
			$client,
			$requestFactory,
			'uri'
		);

		$api->register($this->createMock(SigningPublicKey::class));
	}

	public function testRegister()
	{
		self::markTestIncomplete('todo');
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRevokeUnauthorized()
	{
		$client = $this->createMock(HttpClient::class);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);

		$api = new Api(
			$client,
			$requestFactory,
			'uri'
		);

		$api->revoke('id', $this->createMock(SigningPublicKey::class));
	}

	public function testRevoke()
	{
		self::markTestIncomplete('todo');
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testPublishUnauthorized()
	{
		$client = $this->createMock(HttpClient::class);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);

		$api = new Api(
			$client,
			$requestFactory,
			'uri'
		);

		$api->publish('foo');
	}

	public function testPublish()
	{
		self::markTestIncomplete('todo');
	}

	public function testReplica()
	{
		$client = $this->createMock(HttpClient::class);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);

		$publicKey = $this->createMock(SigningPublicKey::class);

		$api = new Api(
			$client,
			$requestFactory,
			'uri',
			$publicKey
		);

		$replica = $api->replica(1);

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
		self::assertEquals(1, $reflectionPropertySource->getValue($replica));

		$reflectionPropertyChroniclePublicKey = new \ReflectionProperty(Replica::class, 'chroniclePublicKey');
		$reflectionPropertyChroniclePublicKey->setAccessible(\true);
		self::assertSame($publicKey, $reflectionPropertyChroniclePublicKey->getValue($replica));
	}

	public function testReplicas()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);
		$response->expects(self::once())->method('getHeader')->with(Sapient::HEADER_SIGNATURE_NAME)
			->willReturn(['Ypkdmzl7uoEmsNf5htTSmRFWKYpQskL5p3ffMjEQq4oHrwrkhQfJ1Pu9v9NF7Mth5Foa6JfSsJLcveU33pUtAQ==']);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

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

		self::assertEquals(['result'], $api->replicas());
	}

}
