<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpAsyncClient;
use Http\Promise\Promise;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Adapter\Generic\Stream;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Api extends AbstractApi implements ApiInterface
{

	const CHRONICLE_CLIENT_KEY_ID = 'Chronicle-Client-Key-ID';

	/**
	 * @var HttpAsyncClient
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
		HttpAsyncClient $client,
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

	public function lastHash(): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lasthash',
				$this->chronicleUri
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function lookup(string $hash): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lookup/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function since(string $hash): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/since/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function export(): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/export',
				$this->chronicleUri
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function index(): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle',
				$this->chronicleUri
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function register(SigningPublicKey $publicKey, string $comment = \null): Promise
	{
		$message = \json_encode([
			'publickey' => $publicKey->getString(),
			'comment' => $comment,
		]);
		/** @var RequestInterface $request */
		$request = $this->authenticateAndSignMessage($this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/register', $this->chronicleUri)
		)->withBody(Stream::fromString($message))->withHeader(
			'Content-Type',
			'application/json'
		));
		return $this->client->sendAsyncRequest($request)->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function revoke(string $clientId, SigningPublicKey $publicKey): Promise
	{
		$message = \json_encode([
			'clientid' => $clientId,
			'publickey' => $publicKey->getString(),
		]);
		/** @var RequestInterface $request */
		$request = $this->authenticateAndSignMessage($this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/revoke', $this->chronicleUri)
		)->withBody(Stream::fromString($message))->withHeader(
			'Content-Type',
			'application/json'
		));
		return $this->client->sendAsyncRequest($request)->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	public function publish(string $message): Promise
	{
		/** @var RequestInterface $request */
		$request = $this->authenticateAndSignMessage($this->requestFactory->createRequest(
			'POST',
			\sprintf('%s/chronicle/publish', $this->chronicleUri)
		)->withBody(Stream::fromString($message)));
		return $this->client->sendAsyncRequest($request)->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
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

	public function replicas(): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica',
				$this->chronicleUri
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

	private function authenticateAndSignMessage(MessageInterface $request): MessageInterface
	{
		if ($this->signingSecretKey === \null || $this->chronicleClientId === \null) {
			throw new UnauthenticatedException('First use the authenticate() method to set credentials');
		}
		return $request->withHeader(
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				(string) $request->getBody(),
				$this->signingSecretKey->getString(\true)
			))
		);
	}

}
