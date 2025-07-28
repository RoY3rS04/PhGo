<?php

namespace Royers\Http;

class ServeMux
{
    private array $routes;

    public function handleFunc(string $route, Method $method, callable $fn)
    {
        $this->setRoute($route, $method, $fn);
    }

    private function setRoute(string $route, Method $method, callable $fn)
    {
        $this->routes["$route:$method->value"] = $fn;
    }

    public function listen(): void
    {
        $url = $_SERVER["REQUEST_URI"];
        $httpMethod = $_SERVER["REQUEST_METHOD"];

        if (!isset($this->routes["$url:$httpMethod"])) {
            echo "404 NOT FOUND";
            return;
        }

        $this->routes["$url:$httpMethod"](
            new Response(),
            new Request()
        );
    }

}
