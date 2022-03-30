<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;

trait AuthorizeAny
{
    public function authorizeAny(array $abilities, $arguments = [])
    {
        $authorized = app(Gate::class)
            ->forUser(auth()->user())
            ->any($abilities, $arguments);

        if ($authorized) {
            return Response::allow();
        } else {
            throw new AuthorizationException();
        }
    }
}
