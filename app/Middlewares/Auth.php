<?php

namespace App\Middlewares;

use MVC\Middleware;
use MVC\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Auth extends Middleware
{
    public function handle(Request $request, Response $response, Session $session): void
    {
        if (! isAuth()) {
            $response->setStatusCode(Response::HTTP_FOUND);
            $response->headers->set('Location', '/connexion');
            $response->send();
        }
    }
}
