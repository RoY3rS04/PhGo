<?php

namespace Royers\Http;

interface ResponseWriter
{
    public function header(): Header;
    public function writeHeader(StatusCode $code): void;
    public function write(string $content);
}
