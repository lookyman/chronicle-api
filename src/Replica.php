<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use ParagonIE\Sapient\Adapter\ConvenienceInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;

final class Replica implements CommonEndpointInterface
{

	/**
	 * @var Sapient
	 */
	private $sapient;

	/**
	 * @var HttpClient
	 */
	private $client;

	/**
	 * @var SigningSecretKey
	 */
	private $signingSecretKey;

	/**
	 * @var SigningPublicKey
	 */
	private $chroniclePublicKey;

	/**
	 * @var string
	 */
	private $chronicleUri;

	/**
	 * @var int
	 */
	private $source;

	public function __construct(
		Sapient $sapient,
		HttpClient $client,
		SigningSecretKey $signingSecretKey,
		SigningPublicKey $chroniclePublicKey,
		string $chronicleUri,
		int $source
	) {
		$this->sapient = $sapient;
		if (!$this->sapient->getAdapter() instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		$this->client = $client;
		$this->signingSecretKey = $signingSecretKey;
		$this->chroniclePublicKey = $chroniclePublicKey;
		$this->chronicleUri = $chronicleUri;
		$this->source = $source;
	}

	public function lastHash(): array
	{
		/** @var ConvenienceInterface $adapter */
		$adapter = $this->sapient->getAdapter();
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/replica/%s/lasthash', $this->chronicleUri, $this->source),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function lookup(string $hash): array
	{
		/** @var ConvenienceInterface $adapter */
		$adapter = $this->sapient->getAdapter();
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/replica/%s/lookup/%s', $this->chronicleUri, $this->source, \urlencode($hash)),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function since(string $hash): array
	{
		/** @var ConvenienceInterface $adapter */
		$adapter = $this->sapient->getAdapter();
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/replica/%s/since/%s', $this->chronicleUri, $this->source, \urlencode($hash)),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function export(): array
	{
		/** @var ConvenienceInterface $adapter */
		$adapter = $this->sapient->getAdapter();
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/replica/%s/export', $this->chronicleUri, $this->source),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

}
