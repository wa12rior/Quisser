<?php
namespace App\Middleware;

class GuestMiddleware extends Middleware {
    public function __invoke($request, $response, $next) {
        if ($this->container->auth->check()) {
            $this->container->flash->addMessage('info', 'You are logged in.');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}