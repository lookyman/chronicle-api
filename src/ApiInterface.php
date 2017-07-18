<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;

interface ApiInterface extends CommonEndpointInterface
{

	public function index(): array;

	public function register(SigningPublicKey $publicKey, string $comment = null): array;

	public function revoke(string $clientId, SigningPublicKey $publicKey): array;

	public function publish(string $message): array;

	public function replica(string $source): CommonEndpointInterface;

	public function replicas(): array;

}
