<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;

final class Replica extends AbstractApi implements CommonEndpointInterface
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
	 * @var string
	 */
	private $source;

	public function __construct(
		HttpClient $client,
		RequestFactoryInterface $requestFactory,
		string $chronicleUri,
		string $source,
		SigningPublicKey $chroniclePublicKey = \null
	) {
		parent::__construct($chroniclePublicKey);
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
		$this->source = $source;
	}

	public function lastHash(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/lasthash',
				$this->chronicleUri,
				\urlencode($this->source)
			)
		)));
	}

	public function lookup(string $hash): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/lookup/%s',
				$this->chronicleUri,
				\urlencode($this->source),
				\urlencode($hash)
			)
		)));
	}

	public function since(string $hash): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/since/%s',
				$this->chronicleUri,
				\urlencode($this->source),
				\urlencode($hash)
			)
		)));
	}

	public function export(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/export',
				$this->chronicleUri,
				\urlencode($this->source)
			)
		)));
	}

}
