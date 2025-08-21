<?php

namespace Royers\Http;

class Request
{
    // TODO implement the whole Request struct from net/http
    public Header $header;
    public string $routeStruct;
    public string $routeUri;
    public string $method;
    public string $proto;
    public int $protoMajor;
    public int $protoMinor;
    private array $body;
    public QueryParams $queryParams;
    public array $dynamicParams;

    public function __construct(string $routeStruct)
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->proto = $_SERVER["SERVER_PROTOCOL"];
        $this->routeStruct = $routeStruct;
        $this->routeUri = $_SERVER["REQUEST_URI"];

        $protos = explode('.', explode('/', $this->proto)[1]);

        $this->protoMajor = (int)$protos[0];
        $this->protoMinor = (int)$protos[1];

        $this->header = new Header();

        $this->body = $this->getRequestBody(Method::from($this->method));
        $this->queryParams = new QueryParams();
        $this->dynamicParams = $this->getDynamics($this->routeStruct, $this->routeUri);
    }

    private function getRequestBody(Method $reqMethod): array
    {
        return match ($reqMethod) {
            Method::Post => (function () {
                $content = file_get_contents('php://input');
                $data = json_decode($content, true);

                return [...$_POST, ...$_FILES, ...$data];
            })(),
            Method::Put, Method::Patch, Method::Delete => (function () {
                $content = file_get_contents('php://input');
                $data = json_decode($content, true);

                return $data;
            })(),
            default => [],
        };
    }

    public function getBody(): array
    {
        return $this->body;
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

            if ($route[$i] == '?') {
                break;
            }

            $val .= $route[$i];
        }

        $values[] = $val;

        foreach ($params as $key => $pos) {
            $params[$key] = $values[$pos];
        }

        return $params;
    }

}
