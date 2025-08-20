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

        $routeUrl = $this->getRouteRegex($route);

        $this->routes[$routeUrl] = [
          "method" => $method->value,
          "handler" => $fn,
          "isRegex" => $routeUrl != $route,
          "routeStruct" => $route
        ];
    }

    public function listen(): void
    {
        $url = $_SERVER["REQUEST_URI"];
        $httpMethod = $_SERVER["REQUEST_METHOD"];

        $found = false;

        $parsedUrl = parse_url($url, PHP_URL_PATH);

        foreach ($this->routes as $routeRegex => $route) {

            if ((preg_match($routeRegex, $parsedUrl) || (!$route['isRegex'] && $routeRegex == $parsedUrl)) && $httpMethod == $route['method']) {
                $route['handler'](
                    new Response(),
                    new Request(routeStruct: $route['routeStruct'])
                );

                $found = true;

                break;
            }
        }

        if (!$found) {
            echo "404 NOT FOUND";
            return;
        }
    }

    public static function getRouteRegex(string $routeStruct): string
    {

        if (!str_contains($routeStruct, '{')) {
            return $routeStruct;
        }

        $inDynamic = false;
        $routeRegex = '#^';

        for ($i = 0; $i < strlen($routeStruct); $i++) {

            if ($routeStruct[$i] == '{') {
                $inDynamic = true;
            } elseif ($routeStruct[$i] == '}') {
                $inDynamic = false;
            } elseif ($inDynamic) {
                continue;
            } else {
                $routeRegex .= $routeStruct[$i];
            }
        }

        $routeParts = explode('/', $routeRegex);

        array_walk($routeParts, function ($part, $idx) use (&$routeParts) {
            $routeParts[$idx] = $part == ''
                ? '[A-Za-z0-9_-]+'
                : $part;
        });

        $routeRegex = implode('/', $routeParts);
        $routeRegex .= '$#';

        return $routeRegex;
    }

    private function getDynamics(string $routeStruct, string $route): array
    {

        $key = '';
        $insideParam = false;
        $count = 0;

        $params = [];

        for ($i = 1; $i < strlen($routeStruct); $i++) {

            if ($routeStruct[$i] == '/') {
                $count++;
                continue;
            }

            if ($routeStruct[$i] == '{') {
                $insideParam = true;
                continue;
            } elseif ($routeStruct[$i] == '}') {
                $params[$key] = $count;
                $key = '';
                $insideParam = false;
            }

            if ($insideParam) {
                $key .= $routeStruct[$i];
            }
        }

        $values = [];
        $val = '';

        for ($i = 1; $i < strlen($route); $i++) {
            if ($route[$i] == '/') {
                $values[] = $val;
                $val = '';
                continue;
            }
            $val .= $route[$i];
        }

        $values[] = $val;

        foreach ($params as $key => $pos) {
            echo $pos . "<br>";
            var_dump($values);
            $params[$key] = $values[$pos];
        }

        return $params;
    }

}
