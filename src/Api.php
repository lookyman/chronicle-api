<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Adapter\Generic\Stream;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;

final class Api implements ApiInterface
{

	const CHRONICLE_CLIENT_KEY_ID = 'Chronicle-Client-Key-ID';

	const HEADER_SIGNATURE_NAME = 'Body-Signature-Ed25519';

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
	 * @var SigningSecretKey|null
	 */
	private $signingSecretKey;

	/**
	 * @var string|null
	 */
	private $chronicleClientId;

	public function __construct(HttpClient $client, RequestFactoryInterface $requestFactory, string $chronicleUri)
	{
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
	}

	public function authorize(SigningSecretKey $signingSecretKey, string $chronicleClientId)
	{
		$this->signingSecretKey = $signingSecretKey;
		$this->chronicleClientId = $chronicleClientId;
	}

	public function lastHash(): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/lasthash',
					$this->chronicleUri
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
					'%s/chronicle/lookup/%s',
					$this->chronicleUri,
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
					'%s/chronicle/since/%s',
					$this->chronicleUri,
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
					'%s/chronicle/export',
					$this->chronicleUri
				)
			))->getBody(),
			\true
		);
	}

	public function index(): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle',
					$this->chronicleUri
				)
			))->getBody(),
			\true
		);
	}

	public function register(SigningPublicKey $publicKey, string $comment = null): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authorize() method to set credentials');
		}
		$body = Stream::fromString(\json_encode([
			'publickey' => $publicKey->getString(),
			'comment' => $comment,
		]));
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'POST',
				\sprintf('%s/chronicle/register', $this->chronicleUri)
			)->withBody($body)->withHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)->withHeader('Content-Type', 'application/json')->withHeader(
				self::HEADER_SIGNATURE_NAME,
				Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
					(string) $body,
					$this->signingSecretKey->getString(\true)
				))
			))->getBody(),
			\true
		);
	}

	public function revoke(string $clientId, SigningPublicKey $publicKey): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authorize() method to set credentials');
		}
		$body = Stream::fromString(\json_encode([
			'clientid' => $clientId,
			'publickey' => $publicKey->getString(),
		]));
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'POST',
				\sprintf('%s/chronicle/revoke', $this->chronicleUri)
			)->withBody($body)->withHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)->withHeader('Content-Type', 'application/json')->withHeader(
				self::HEADER_SIGNATURE_NAME,
				Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
					(string) $body,
					$this->signingSecretKey->getString(\true)
				))
			))->getBody(),
			\true
		);
	}

	public function publish(string $message): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authorize() method to set credentials');
		}
		$body = Stream::fromString($message);
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'POST',
				\sprintf('%s/chronicle/publish', $this->chronicleUri)
			)->withBody($body)->withHeader(
				self::CHRONICLE_CLIENT_KEY_ID,
				$this->chronicleClientId
			)->withHeader(
				self::HEADER_SIGNATURE_NAME,
				Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
					(string) $body,
					$this->signingSecretKey->getString(\true)
				))
			))->getBody(),
			\true
		);
	}

	public function replica(int $source): CommonEndpointInterface
	{
		return new Replica(
			$this->client,
			$this->requestFactory,
			$this->chronicleUri,
			$source
		);
	}

	public function replicas(): array
	{
		return \json_decode(
			(string) $this->client->sendRequest($this->requestFactory->createRequest(
				'GET',
				\sprintf(
					'%s/chronicle/replica',
					$this->chronicleUri
				)
			))->getBody(),
			\true
		);
	}

}
