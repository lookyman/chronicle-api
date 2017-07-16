<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Client\HttpClient;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\Exception\HeaderMissingException;
use ParagonIE\Sapient\Exception\InvalidMessageException;
use ParagonIE\Sapient\Sapient;

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

	/**
	 * @var SigningPublicKey|null
	 */
	private $chroniclePublicKey;

	public function __construct(
		HttpClient $client,
		RequestFactoryInterface $requestFactory,
		string $chronicleUri,
		int $source,
		SigningPublicKey $chroniclePublicKey = \null
	) {
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->chronicleUri = $chronicleUri;
		$this->source = $source;
		$this->chroniclePublicKey = $chroniclePublicKey;
	}

	public function lastHash(): array
	{
		$response = $this->client->sendRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/lasthash',
				$this->chronicleUri,
				$this->source
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
				'%s/chronicle/replica/%s/lookup/%s',
				$this->chronicleUri,
				$this->source,
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
				'%s/chronicle/replica/%s/since/%s',
				$this->chronicleUri,
				$this->source,
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
				'%s/chronicle/replica/%s/export',
				$this->chronicleUri,
				$this->source
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
