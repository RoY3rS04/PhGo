<?php

namespace Royers\Http;

class Request
{
    // TODO implement the whole Request struct from net/http
    public Header $header;

    public function __construct()
    {
        $this->header = new Header();
    }
}
