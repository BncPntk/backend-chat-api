<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards): void
    {
        abort(response()->json([
            'message' => 'Nincs jogosultság.'
        ], 401));
    }
}
