<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Adapter\AdapterInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Lookyman\Chronicle\Api
 */
final class ApiTest extends TestCase
{

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $sapient;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $client;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $signingSecretKey;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	private $chroniclePublicKey;

	/**
	 * @var Api
	 */
	private $api;

	protected function setUp()
	{
		$this->sapient = $this->createMock(Sapient::class);
		$this->client = $this->createMock(HttpClient::class);
		$this->signingSecretKey = $this->createMock(SigningSecretKey::class);
		$this->chroniclePublicKey = $this->createMock(SigningPublicKey::class);
		$this->api = new Api(
			$this->sapient,
			$this->client,
			$this->signingSecretKey,
			$this->chroniclePublicKey,
			'uri',
			'clientId'
		);
	}

	/**
	 * @dataProvider implementsInterfaceProvider
	 */
	public function testImplementsInterface(string $interface)
	{
		$ref = new \ReflectionClass(Api::class);
		self::assertTrue($ref->implementsInterface($interface));
	}

	public function implementsInterfaceProvider(): array
	{
		return [
			'api' => [ApiInterface::class],
			'common endpoint' => [CommonEndpointInterface::class],
		];
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testLastHashInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->lastHash();
	}

	public function testLastHash()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/lasthash',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->lastHash());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testLookupInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->lookup('');
	}

	public function testLookup()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/lookup/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->lookup('foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSinceInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->since('');
	}

	public function testSince()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/since/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->since('foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testExportInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->export();
	}

	public function testExport()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/export',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->export());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testIndexInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->index();
	}

	public function testIndex()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->index());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRegisterInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		/** @var string $decodedPublicKey */
		$decodedPublicKey = Base64UrlSafe::decode('GB3bAfPYdFR7yCeIWeZ3Xm7hzmuFTrDnEnZtHhG1zjg=');
		$this->api->register(new SigningPublicKey($decodedPublicKey));
	}

	public function testRegister()
	{
		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::once())->method('withAddedHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'clientId'
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);

		/** @var string $decodedPublicKey */
		$decodedPublicKey = Base64UrlSafe::decode('GB3bAfPYdFR7yCeIWeZ3Xm7hzmuFTrDnEnZtHhG1zjg=');
		$publicKey = new SigningPublicKey($decodedPublicKey);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedJsonRequest')->with(
			'POST',
			'uri/chronicle/register',
			[
				'publickey' => $publicKey->getString(),
				'comment' => 'foo',
			],
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->register($publicKey, 'foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRevokeInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		/** @var string $decodedPublicKey */
		$decodedPublicKey = Base64UrlSafe::decode('GB3bAfPYdFR7yCeIWeZ3Xm7hzmuFTrDnEnZtHhG1zjg=');
		$this->api->revoke('id', new SigningPublicKey($decodedPublicKey));
	}

	public function testRevoke()
	{
		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::once())->method('withAddedHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'clientId'
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);

		/** @var string $decodedPublicKey */
		$decodedPublicKey = Base64UrlSafe::decode('GB3bAfPYdFR7yCeIWeZ3Xm7hzmuFTrDnEnZtHhG1zjg=');
		$publicKey = new SigningPublicKey($decodedPublicKey);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedJsonRequest')->with(
			'POST',
			'uri/chronicle/revoke',
			[
				'clientid' => 'id',
				'publickey' => $publicKey->getString(),
			],
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->revoke('id', $publicKey));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testPublishInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->publish('foo');
	}

	public function testPublish()
	{
		$request = $this->createMock(RequestInterface::class);
		$request->expects(self::once())->method('withAddedHeader')->with(
			Api::CHRONICLE_CLIENT_KEY_ID,
			'clientId'
		)->willReturn($request);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'POST',
			'uri/chronicle/publish',
			'foo',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->publish('foo'));
	}

	public function testReplica()
	{
		$replica = $this->api->replica(1);
		self::assertInstanceOf(Replica::class, $replica);

		$reflectionPropertySapient = new \ReflectionProperty(Replica::class, 'sapient');
		$reflectionPropertySapient->setAccessible(true);
		self::assertSame($this->sapient, $reflectionPropertySapient->getValue($replica));

		$reflectionPropertyClient = new \ReflectionProperty(Replica::class, 'client');
		$reflectionPropertyClient->setAccessible(true);
		self::assertSame($this->client, $reflectionPropertyClient->getValue($replica));

		$reflectionPropertySigningSecretKey = new \ReflectionProperty(Replica::class, 'signingSecretKey');
		$reflectionPropertySigningSecretKey->setAccessible(true);
		self::assertSame($this->signingSecretKey, $reflectionPropertySigningSecretKey->getValue($replica));

		$reflectionPropertyChroniclePublicKey = new \ReflectionProperty(Replica::class, 'chroniclePublicKey');
		$reflectionPropertyChroniclePublicKey->setAccessible(true);
		self::assertSame($this->chroniclePublicKey, $reflectionPropertyChroniclePublicKey->getValue($replica));

		$reflectionPropertyChronicleUri = new \ReflectionProperty(Replica::class, 'chronicleUri');
		$reflectionPropertyChronicleUri->setAccessible(true);
		self::assertSame('uri', $reflectionPropertyChronicleUri->getValue($replica));

		$reflectionPropertySource = new \ReflectionProperty(Replica::class, 'source');
		$reflectionPropertySource->setAccessible(true);
		self::assertSame(1, $reflectionPropertySource->getValue($replica));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testReplicasInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->api->replicas();
	}

	public function testReplicas()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->api->replicas());
	}

}
