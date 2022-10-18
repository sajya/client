## Introduction

>  **Warning**.   
> This package is still under development and is not recommended for use in production.

This package lets you set up a JSON-RPC client over HTTP(S), using your PHP code to make the requests. Built
around [Laravel](https://laravel.com/docs/8.x/http-client#introduction) expressive HTTP wrapper, it allows you to
customize things like authorization, retries, and more.

## Usage

```php
use Illuminate\Support\Facades\Http;
use Sajya\Client\Client;

$client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

$response = $client->execute('tennis@ping');

$response->result(); // pong
```

Example with positional parameters:

```php
$response = $client->execute('tennis@ping', [3, 5]);
```

Example with named arguments:

```php
$response = $client->execute('tennis@ping', ['end' => 10, 'start' => 1]);
```

### Batch requests

Call several procedures in a single HTTP request:

```php
$batchData = $client->batch(function (Client $client) {
    $client->execute('tennis@ping');
    $client->execute('tennis@ping');
});
```

### Notify requests

```php
$client->notify('procedure@method');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
