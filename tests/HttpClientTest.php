<?php

declare(strict_types=1);

namespace Sajya\Client\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sajya\Client\Client;
use Sajya\Server\Guide;

class HttpClientTest extends TestCase
{
    public function testExecute(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $response = $client->execute('fixture@ok');

        $this->assertEquals('Ok', $response->result());
        $this->assertNotNull($response->id());
        $this->assertNull($response->error());
    }

    public function testExecuteId(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $response = $client->execute('fixture@ok', null, 'any-id');

        $this->assertEquals('any-id', $response->id());
    }

    public function testNotify(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $response = $client->notify('fixture@ok');

        $this->assertEmpty($response->id());
    }

    public function testExecuteArgument(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $response = $client->execute('fixture@sum', ['a' => 1, 'b' => 2]);

        $this->assertEquals(3, $response->result());
        $this->assertNotNull($response->id());
        $this->assertNull($response->error());
    }

    public function testExecuteError(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $response = $client->execute('fixture@runtimeError');

        $this->assertNull($response->result());
        $this->assertNotNull($response->id());
        $this->assertNotNull($response->error());
    }

    public function testBathRequest(): void
    {
        $client = new Client(Http::baseUrl('http://localhost:8000/api/v1/endpoint'));

        $batch = $client->batch(function (Client $client) {
            $client->execute('fixture@sum', ['a' => 100, 'b' => 100], 'first');
            $client->execute('fixture@sum', ['a' => 50, 'b' => 50], 'second');
            $client->execute('fixture@runtimeError', null, 'third');
            $client->notify('fixture@ok');
        });

        $this->assertCount(3, $batch);

        $this->assertEquals(200, $batch->get('first')->result());
        $this->assertEquals(100, $batch->get('second')->result());

        $this->assertNotNull($batch->get('third')->error());
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake(function (Request $request) {
            $guide = new Guide([FixtureProcedure::class]);
            $response = $guide->terminate($request->body());

            return Http::response(json_encode($response, JSON_THROW_ON_ERROR));
        });
    }
}
