<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use Http\Promise\Promise;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;

interface ApiInterface extends CommonEndpointInterface
{

	public function index(): Promise;

	public function register(SigningPublicKey $publicKey, string $comment = null): Promise;

	public function revoke(string $clientId, SigningPublicKey $publicKey): Promise;

	public function publish(string $message): Promise;

	public function replica(string $source): CommonEndpointInterface;

	public function replicas(): Promise;

}
