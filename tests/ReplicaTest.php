<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use ParagonIE\Sapient\Adapter\AdapterInterface;
use ParagonIE\Sapient\Adapter\Guzzle;
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
	private $adapter;

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
		$this->adapter = $this->createMock(Guzzle::class);
		$this->sapient = $this->createMock(Sapient::class);
		$this->sapient->expects(self::any())->method('getAdapter')->willReturn($this->adapter);
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
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidAdapter()
	{
		$this->sapient = $this->createMock(Sapient::class);
		$this->sapient->expects(self::any())->method('getAdapter')->willReturn($this->createMock(AdapterInterface::class));
		$this->replica = new Replica(
			$this->sapient,
			$this->client,
			$this->signingSecretKey,
			$this->chroniclePublicKey,
			'uri',
			1
		);
	}

	public function testLastHash()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$this->adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lasthash',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::once())->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->lastHash());
	}

	public function testLookup()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$this->adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lookup/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::once())->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->lookup('foo'));
	}

	public function testSince()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$this->adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/since/foo',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::once())->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->since('foo'));
	}

	public function testExport()
	{
		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);

		$this->adapter->expects(self::once())->method('createSignedRequest')->with(
			'GET',
			'uri/chronicle/replica/1/export',
			'',
			$this->signingSecretKey
		)->willReturn($request);

		$this->sapient->expects(self::once())->method('decodeSignedJsonResponse')->with(
			$response,
			$this->chroniclePublicKey
		)->willReturn(['result']);

		$this->client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		self::assertEquals(['result'], $this->replica->export());
	}

}
