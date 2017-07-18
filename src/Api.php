<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Adapter\Generic\Stream;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;

final class Api extends AbstractApi implements ApiInterface
{

	const CHRONICLE_CLIENT_KEY_ID = 'Chronicle-Client-Key-ID';

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

	public function __construct(
		HttpClient $client,
		RequestFactoryInterface $requestFactory,
		string $chronicleUri,
		SigningPublicKey $chroniclePublicKey = \null
	) {
		parent::__construct($chroniclePublicKey);
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
	}

	public function authenticate(SigningSecretKey $signingSecretKey, string $chronicleClientId)
	{
		$this->signingSecretKey = $signingSecretKey;
		$this->chronicleClientId = $chronicleClientId;
	}

	public function lastHash(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lasthash',
				$this->chronicleUri
			)
		)));
	}

	public function lookup(string $hash): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lookup/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		)));
	}

	public function since(string $hash): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/since/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		)));
	}

	public function export(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/export',
				$this->chronicleUri
			)
		)));
	}

	public function index(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle',
				$this->chronicleUri
			)
		)));
	}

	public function register(SigningPublicKey $publicKey, string $comment = \null): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authenticate() method to set credentials');
		}
		$message = \json_encode([
			'publickey' => $publicKey->getString(),
			'comment' => $comment,
		]);
		/** @var RequestInterface $request */
		$request = $this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/register', $this->chronicleUri)
		)->withBody(Stream::fromString($message))->withHeader(
			'Content-Type',
			'application/json'
		)->withHeader(
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				$message,
				$this->signingSecretKey->getString(\true)
			))
		);
		return $this->verifyAndReturnResponse($this->client->sendRequest($request));
	}

	public function revoke(string $clientId, SigningPublicKey $publicKey): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authenticate() method to set credentials');
		}
		$message = \json_encode([
			'clientid' => $clientId,
			'publickey' => $publicKey->getString(),
		]);
		/** @var RequestInterface $request */
		$request = $this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/revoke', $this->chronicleUri)
		)->withBody(Stream::fromString($message))->withHeader(
			'Content-Type',
			'application/json'
		)->withHeader(
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				$message,
				$this->signingSecretKey->getString(\true)
			))
		);
		return $this->verifyAndReturnResponse($this->client->sendRequest($request));
	}

	public function publish(string $message): array
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new \InvalidArgumentException('First use the authenticate() method to set credentials');
		}
		/** @var RequestInterface $request */
		$request = $this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/publish', $this->chronicleUri)
		)->withBody(Stream::fromString($message))->withHeader(
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				$message,
				$this->signingSecretKey->getString(\true)
			))
		);
		return $this->verifyAndReturnResponse($this->client->sendRequest($request));
	}

	public function replica(string $source): CommonEndpointInterface
	{
		return new Replica(
			$this->client,
			$this->requestFactory,
			$this->chronicleUri,
			$source,
			$this->getChroniclePublicKey()
		);
	}

	public function replicas(): array
	{
		return $this->verifyAndReturnResponse($this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica',
				$this->chronicleUri
			)
		)));
	}

}
