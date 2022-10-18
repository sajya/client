<?php

declare(strict_types=1);

namespace Sajya\Client\Tests;

use Illuminate\Foundation\Application;
use Sajya\Server\ServerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Some tests use log files for verification.
     * To prevent past results from affecting, clear all logs
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServerServiceProvider::class,
        ];
    }
}
