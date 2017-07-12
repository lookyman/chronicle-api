<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use ParagonIE\Sapient\Adapter\ConvenienceInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;

final class Api implements ApiInterface
{

	const CHRONICLE_CLIENT_KEY_ID = 'Chronicle-Client-Key-ID';

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
	 * @var string
	 */
	private $chronicleClientId;

	public function __construct(
		Sapient $sapient,
		HttpClient $client,
		SigningSecretKey $signingSecretKey,
		SigningPublicKey $chroniclePublicKey,
		string $chronicleUri,
		string $chronicleClientId
	) {
		$this->sapient = $sapient;
		$this->client = $client;
		$this->signingSecretKey = $signingSecretKey;
		$this->chroniclePublicKey = $chroniclePublicKey;
		$this->chronicleUri = $chronicleUri;
		$this->chronicleClientId = $chronicleClientId;
	}

	public function lastHash(): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/lasthash', $this->chronicleUri),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function lookup(string $hash): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/lookup/%s', $this->chronicleUri, \urlencode($hash)),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function since(string $hash): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/since/%s', $this->chronicleUri, \urlencode($hash)),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function export(): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/export', $this->chronicleUri),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function index(): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle', $this->chronicleUri),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

	public function register(SigningPublicKey $publicKey, string $comment = null): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedJsonRequest(
				'POST',
				\sprintf('%s/chronicle/register', $this->chronicleUri),
				[
					'publickey' => $publicKey->getString(),
					'comment' => $comment,
				],
				$this->signingSecretKey
			)->withAddedHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)),
			$this->chroniclePublicKey
		);
	}

	public function revoke(string $clientId, SigningPublicKey $publicKey): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedJsonRequest(
				'POST',
				\sprintf('%s/chronicle/revoke', $this->chronicleUri),
				[
					'clientid' => $clientId,
					'publickey' => $publicKey->getString(),
				],
				$this->signingSecretKey
			)->withAddedHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)),
			$this->chroniclePublicKey
		);
	}

	public function publish(string $message): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'POST',
				\sprintf('%s/chronicle/publish', $this->chronicleUri),
				$message,
				$this->signingSecretKey
			)->withAddedHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)),
			$this->chroniclePublicKey
		);
	}

	public function replica(int $source): CommonEndpointInterface
	{
		return new Replica(
			$this->sapient,
			$this->client,
			$this->signingSecretKey,
			$this->chroniclePublicKey,
			$this->chronicleUri,
			$source
		);
	}

	public function replicas(): array
	{
		$adapter = $this->sapient->getAdapter();
		if (!$adapter instanceof ConvenienceInterface) {
			throw new \InvalidArgumentException(\sprintf('Sapient adapter must be an instance of %s.', ConvenienceInterface::class));
		}
		return $this->sapient->decodeSignedJsonResponse(
			$this->client->sendRequest($adapter->createSignedRequest(
				'GET',
				\sprintf('%s/chronicle/replica', $this->chronicleUri),
				'',
				$this->signingSecretKey
			)),
			$this->chroniclePublicKey
		);
	}

}
