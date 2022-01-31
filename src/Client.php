<?php

declare(strict_types=1);

namespace Sajya\Client;

use Illuminate\Support\Collection;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;

class Client
{
    /**
     * @var array
     */
    protected array $batch = [];

    /**
     * @var bool
     */
    protected bool $isBatch = false;

    /**
     * @var \Illuminate\Http\Client\Factory
     */
    protected $http;

    /**
     * @param \Illuminate\Http\Client\PendingRequest $http
     */
    public function __construct(PendingRequest $http)
    {
        $this->http = $http->acceptJson()->asJson();
    }

    /**
     * @param callable $callback
     *
     * @return
     */
    public function batch(callable $callback)
    {
        $this->isBatch = true;

        $callback($this);

        $response = $this->http->post('', $this->batch);

        $this->isBatch = false;
        $this->batch = [];

        return $response->collect()->map(fn($content) => $this->prepareResponse(collect($content), $response));
    }

    /**
     * @param \Illuminate\Support\Collection                                        $data
     * @param \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response $response
     *
     * @return \Sajya\Client\Response
     */
    protected function prepareResponse(Collection $data, $response)
    {
        return new Response(
            $data->get('id'),
            $data->get('result'),
            $data->get('error'),
            $response
        );
    }

    /**
     * @param string      $method
     * @param array|null  $params
     * @param string|null $id
     *
     * @return \Sajya\Client\Response|void
     */
    public function execute(string $method, array $params = null, string $id = null)
    {
        return $this->request($method, $params, $id ?? Str::uuid()->toString());
    }

    /**
     * @param string      $method
     * @param array       $params
     * @param string|null $id
     *
     * @return $this|\GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    protected function request(string $method, ?array $params, string $id = null)
    {
        $data = collect([
            'jsonrpc' => '2.0',
            'id'      => $id,
            'params'  => $params,
            'method'  => $method,
        ])->filter()->toArray();

        if ($this->isBatch) {
            $this->batch[] = $data;
        }

        return $this->call($data);
    }

    /**
     * @param array $data
     *
     * @return \Sajya\Client\Response
     */
    protected function call(array $data)
    {
        $response = $this->http->post('', $data);

        return $this->prepareResponse($response->collect(), $response);
    }

    /**
     * @param string $method
     * @param array  $params
     *
     * @return static
     */
    public function notify(string $method, array $params = [])
    {
        return $this->request($method, $params);
    }
}
