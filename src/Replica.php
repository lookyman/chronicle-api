<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;

final class Replica implements CommonEndpointInterface
{

	/**
	 * @var HttpClient
	 */
	private $client;

	/**
	 * @var RequestFactoryInterface
	 */
	private $requestFactory;

	/**
	 * @var string
	 */
	private $chronicleUri;

	/**
	 * @var int
	 */
	private $source;

	public function __construct(
		HttpClient $client,
		RequestFactoryInterface $requestFactory,
		string $chronicleUri,
		int $source
	) {
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
		$this->source = $source;
	}

	public function lastHash(): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/replica/%s/lasthash',
					$this->chronicleUri,
					$this->source
				)
			))->getBody(),
			\true
		);
	}

	public function lookup(string $hash): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/replica/%s/lookup/%s',
					$this->chronicleUri,
					$this->source,
					\urlencode($hash)
				)
			))->getBody(),
			\true
		);
	}

	public function since(string $hash): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/replica/%s/since/%s',
					$this->chronicleUri,
					$this->source,
					\urlencode($hash)
				)
			))->getBody(),
			\true
		);
	}

	public function export(): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/replica/%s/export',
					$this->chronicleUri,
					$this->source
				)
			))->getBody(),
			\true
		);
	}

}
