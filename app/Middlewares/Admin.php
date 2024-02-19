<?php

namespace App\Middlewares;

use MVC\HttpException;
use MVC\Middleware;
use MVC\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Admin extends Middleware
{
    public function handle(Request $request, Response $response, Session $session): void
    {
        if (! isAdmin()) {
            throw new HttpException('Halte-là!', 403);
        }
    }
}
