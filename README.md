# Lookyman/Chronicle

```php
use Lookyman\Chronicle\Api;
use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\CryptographyKeys\SigningPublicKey;
use ParagonIE\Sapient\CryptographyKeys\SigningSecretKey;
use ParagonIE\Sapient\Sapient;
use Psr\Http\Message\RequestInterface;

$api = new Api(
	new Sapient(new Adapter()), // Adapter must implement ParagonIE\Sapient\Adapter\ConvenienceInterface 
	new Client(), // Client must implement Http\Client\HttpClient
	new SigningSecretKey(Base64UrlSafe::decode('your signing key')),
	new SigningPublicKey(Base64UrlSafe::decode('chronicle public key')),
	'https://chronicle-uri.com',
	'your client id' // optional
);

$api->publish('hello world'); // this won't work if you don't set your client id
$api->lastHash(); // this will
```
