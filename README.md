## Introduction

> This package is still under development and is not recommended for use in production.

## Usage

```php
use Illuminate\Support\Facades\Http;
use Sajya\Client\Client;

$client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

$response = $client->execute('tennis@ping');

$response->result(); // pong
```

```php
$batchData = $client->batch(function (Client $client) {
    $client->execute('tennis@ping');
    $client->execute('tennis@ping');
});
```

```php
$client->notify('procedure@method');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
