<?php

namespace Royers\Http;

class Header
{
    public array $headers = [];

    public function __construct()
    {
        $headers = getallheaders();
        $this->headers = $headers != false ? $headers : $this->getHeaders();
    }

    private function getHeaders(): array
    {
        $reqHeaders = [];

        foreach ($_SERVER as $k => $v) {
            $headerKey = strtolower($k);

            if (str_starts_with($headerKey, 'http')) {

                $keyParts = explode('_', $headerKey);

                $newKeyParts = [];

                foreach ($keyParts as $kp) {

                    if ($kp == 'http') {
                        continue;
                    }

                    $newKeyParts[] = ucfirst($kp);
                }

                $headerKey = implode('-', $newKeyParts);

                $reqHeaders[$headerKey] = $v;
            }
        }

        return $reqHeaders;
    }

}
