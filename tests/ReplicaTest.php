<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use ParagonIE\Sapient\Adapter\AdapterInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Lookyman\Chronicle\Replica
 */
final class ReplicaTest extends TestCase
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
	 * @var Replica
	 */
	private $replica;

	protected function setUp()
	{
		$this->sapient = $this->createMock(Sapient::class);
		$this->client = $this->createMock(HttpClient::class);
		$this->signingSecretKey = $this->createMock(SigningSecretKey::class);
		$this->chroniclePublicKey = $this->createMock(SigningPublicKey::class);
		$this->replica = new Replica(
			$this->sapient,
			$this->client,
			$this->signingSecretKey,
			$this->chroniclePublicKey,
			'uri',
			1
		);
	}

	/**
	 * @dataProvider implementsInterfaceProvider
	 */
	public function testImplementsInterface(string $interface)
	{
		$ref = new \ReflectionClass(Replica::class);
		self::assertTrue($ref->implementsInterface($interface));
	}

	public function implementsInterfaceProvider(): array
	{
		return [
			'common endpoint' => [CommonEndpointInterface::class],
		];
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testLastHashInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->replica->lastHash();
	}

	public function testLastHash()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lasthash',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->lastHash());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testLookupInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->replica->lookup('');
	}

	public function testLookup()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lookup/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->lookup('foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testSinceInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->replica->since('');
	}

	public function testSince()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/since/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->since('foo'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testExportInvalidAdapter()
	{
		$this->sapient->expects(self::once())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->replica->export();
	}

	public function testExport()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$adapter = $this->createMock(AdapterMock::class);
		$adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/export',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::at(0))->method('getAdapter')->willReturn($adapter);
		$this->sapient->expects(self::at(1))->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->export());
	}

}
