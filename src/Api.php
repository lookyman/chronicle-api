<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Adapter\Generic\Stream;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Exception\HeaderMissingException;
use ParagonIE\Sapient\Exception\InvalidMessageException;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;

final class Api implements ApiInterface
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

	/**
	 * @var SigningPublicKey|null
	 */
	private $chroniclePublicKey;

	public function __construct(
		HttpClient $client,
		RequestFactoryInterface $requestFactory,
		string $chronicleUri,
		SigningPublicKey $chroniclePublicKey = \null
	) {
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
		$this->chroniclePublicKey = $chroniclePublicKey;
	}

	public function authenticate(SigningSecretKey $signingSecretKey, string $chronicleClientId)
	{
		$this->signingSecretKey = $signingSecretKey;
		$this->chronicleClientId = $chronicleClientId;
	}

	public function lastHash(): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lasthash',
				$this->chronicleUri
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

	public function lookup(string $hash): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/lookup/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

	public function since(string $hash): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/since/%s',
				$this->chronicleUri,
				\urlencode($hash)
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

	public function export(): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/export',
				$this->chronicleUri
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

	public function index(): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle',
				$this->chronicleUri
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
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
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader('Content-Type', 'application/json')->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				$message,
				$this->signingSecretKey->getString(\true)
			))
		);
		$response = $this->client->sendRequest($request);
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
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
			self::CHRONICLE_CLIENT_KEY_ID,
			$this->chronicleClientId
		)->withHeader('Content-Type', 'application/json')->withHeader(
			Sapient::HEADER_SIGNATURE_NAME,
			Base64UrlSafe::encode(\ParagonIE_Sodium_Compat::crypto_sign_detached(
				$message,
				$this->signingSecretKey->getString(\true)
			))
		);
		$response = $this->client->sendRequest($request);
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
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
		$response = $this->client->sendRequest($request);
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

	public function replica(int $source): CommonEndpointInterface
	{
		return new Replica(
			$this->client,
			$this->requestFactory,
			$this->chronicleUri,
			$source,
			$this->chroniclePublicKey
		);
	}

	public function replicas(): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica',
				$this->chronicleUri
			)
		));
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found.', Sapient::HEADER_SIGNATURE_NAME));
			}
			foreach ($headers as $header) {
				if (\ParagonIE_Sodium_Compat::crypto_sign_verify_detached(
					(string) Base64UrlSafe::decode($header),
					$body,
					$this->chroniclePublicKey->getString(\true)
				)) {
					$verified = \true;
					break;
				}
			}
		}
		if ($verified) {
			return \json_decode($body, \true);
		}
		throw new InvalidMessageException('No valid signature given for this HTTP response');
	}

}
