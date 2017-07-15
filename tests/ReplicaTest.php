<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Lookyman\Chronicle\Replica
 */
final class ReplicaTest extends TestCase
{

	public function testLastHash()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lasthash'
		)->willReturn($request);

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			1
		);

		self::assertEquals(['result'], $replica->lastHash());
	}

	public function testLookup()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/1/lookup/foo'
		)->willReturn($request);

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			1
		);

		self::assertEquals(['result'], $replica->lookup('foo'));
	}

	public function testSince()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/1/since/foo'
		)->willReturn($request);

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			1
		);

		self::assertEquals(['result'], $replica->since('foo'));
	}

	public function testExport()
	{
		$stream = $this->createMock(StreamInterface::class);
		$stream->expects(self::once())->method('__toString')->willReturn('["result"]');

		$request = $this->createMock(RequestInterface::class);

		$response = $this->createMock(ResponseInterface::class);
		$response->expects(self::once())->method('getBody')->willReturn($stream);

		$client = $this->createMock(HttpClient::class);
		$client->expects(self::once())->method('sendRequest')->with($request)->willReturn($response);

		$requestFactory = $this->createMock(RequestFactoryInterface::class);
		$requestFactory->expects(self::once())->method('createRequest')->with(
			'GET',
			'uri/chronicle/replica/1/export'
		)->willReturn($request);

		$replica = new Replica(
			$client,
			$requestFactory,
			'uri',
			1
		);

		self::assertEquals(['result'], $replica->export());
	}

}
