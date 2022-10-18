## Introduction

[![run-tests](https://github.com/sajya/client/actions/workflows/run-tests.yml/badge.svg)](https://github.com/sajya/client/actions/workflows/run-tests.yml)

>  **Warning**.   
> This package is still under development and is not recommended for use in production.

This package lets you set up a JSON-RPC client over HTTP(S), using your PHP code to make the requests. Built
around [Laravel](https://laravel.com/docs/8.x/http-client#introduction) expressive HTTP wrapper, it allows you to
customize things like authorization, retries, and more.


## Install

Go to the project directory and run the command:

```php
$ composer require sajya/client
```


## Usage

```php
use Illuminate\Support\Facades\Http;
use Sajya\Client\Client;

$client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

$response = $client->execute('tennis@ping');

$response->result(); // pong
```

By default, the request identifier will be generated using the [UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier), you can get it by calling the `id()` method

```php
$response->id();
```

To get the result of an error, you need to call the `error()` method

```php
$response->error();
```

### Parameters

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
