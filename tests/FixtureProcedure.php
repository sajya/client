<?php

declare(strict_types=1);

namespace Sajya\Client\Tests;

use Illuminate\Http\Request;
use Sajya\Server\Attributes\Procedure;
use Sajya\Server\Exceptions\RuntimeRpcException;

#[Procedure('fixture')]
class FixtureProcedure
{
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
