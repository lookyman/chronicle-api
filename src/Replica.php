<?php
declare(strict_types=1);

namespace Lookyman\Chronicle;

use Http\Client\HttpAsyncClient;
use Http\Promise\Promise;
use Interop\Http\Factory\RequestFactoryInterface;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use Psr\Http\Message\ResponseInterface;

final class Replica extends AbstractApi implements CommonEndpointInterface
{

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
	 * @var string
	 */
	private $source;

	public function __construct(
		HttpAsyncClient $client,
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

	public function lastHash(): Promise
	{
		return $this->client->sendAsyncRequest($this->requestFactory->createRequest(
			'GET',
			\sprintf(
				'%s/chronicle/replica/%s/lasthash',
				$this->chronicleUri,
				\urlencode($this->source)
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
				'%s/chronicle/replica/%s/lookup/%s',
				$this->chronicleUri,
				\urlencode($this->source),
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
				'%s/chronicle/replica/%s/since/%s',
				$this->chronicleUri,
				\urlencode($this->source),
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
				'%s/chronicle/replica/%s/export',
				$this->chronicleUri,
				\urlencode($this->source)
			)
		))->then(function (ResponseInterface $response) {
			return $this->verifyAndReturnResponse($response);
		});
	}

}
