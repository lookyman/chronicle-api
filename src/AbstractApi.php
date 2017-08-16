<?php

declare(strict_types = 1);

namespace Lookyman\Chronicle;

use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\Exception\HeaderMissingException;
use ParagonIE\Sapient\Exception\InvalidMessageException;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApi
{

	/**
	 * @var SigningPublicKey|null
	 */
	private $chroniclePublicKey;

	public function __construct(SigningPublicKey $chroniclePublicKey = \null)
	{
		$this->chroniclePublicKey = $chroniclePublicKey;
	}

	/**
	 * @return SigningPublicKey|null
	 */
	protected function getChroniclePublicKey()
	{
		return $this->chroniclePublicKey;
	}

	protected function verifyAndReturnResponse(ResponseInterface $response): array
	{
		$body = (string) $response->getBody();
		$verified = $this->chroniclePublicKey === \null;
		if ($this->chroniclePublicKey !== \null) {
			$headers = $response->getHeader(Sapient::HEADER_SIGNATURE_NAME);
			if (\count($headers) === 0) {
				throw new HeaderMissingException(\sprintf('No signed response header (%s) found', Sapient::HEADER_SIGNATURE_NAME));
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
		if (!$verified) {
			throw new InvalidMessageException('No valid signature given for this HTTP response');
		}
		return \json_decode($body, \true);
	}

}
