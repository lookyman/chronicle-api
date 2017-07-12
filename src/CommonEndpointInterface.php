<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

interface CommonEndpointInterface
{

	public function lastHash(): array;

	public function lookup(string $hash): array;

	public function since(string $hash): array;

	public function export(): array;

}
