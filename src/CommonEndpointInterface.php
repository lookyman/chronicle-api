<?php
declare(strict_types=1);

namespace Lookyman\Chronicle;

use Http\Promise\Promise;

interface CommonEndpointInterface
{

	public function lastHash(): Promise;

	public function lookup(string $hash): Promise;

	public function since(string $hash): Promise;

	public function export(): Promise;

}
