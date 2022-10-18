<?php

declare(strict_types=1);

namespace Sajya\Client\Tests;

use Illuminate\Http\Request;
use Sajya\Server\Exceptions\RuntimeRpcException;
use Sajya\Server\Procedure;

class FixtureProcedure extends Procedure
{
    /**
     * The name of the procedure that will be
     * displayed and taken into account in the search.
     *
     * @var string
     */
    public static string $name = 'fixture';

    /**
     * @return string
     */
    public function ok(): string
    {
        return 'Ok';
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function sum(Request $request): int
    {
        $request->validate([
            'a' => 'integer|required',
            'b' => 'integer|required',
        ]);

        return $request->get('a') + $request->get('b');
    }

    /**
     * @return mixed
     */
    public function runtimeError()
    {
        throw new RuntimeRpcException();
    }
}
