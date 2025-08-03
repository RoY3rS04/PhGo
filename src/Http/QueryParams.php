<?php

namespace Royers\Http;

class QueryParams
{
    private array $params;

    public function __construct()
    {
        $this->params = $_GET;
    }

    public function get(string ...$params): array|string|null
    {

        if (count($params) < 2) {
            return $this->params[$params[0]];
        }

        $values = [];

        foreach ($params as $param) {
            $values[] = $this->params[$param];
        }

        return $values;
    }

    public function has(string $param): bool
    {
        return isset($this->params[$param]);
    }

    public function entries(): array
    {
        return $this->params;
    }
}
