<?php

namespace Royers\Http;

class Header
{
    private const array COMMON_HEADERS = [
      "content_type",
      "content_length",
      "redirect_http_authorization"
    ];

    private const array VALID_HEADER_SEPARATORS = [
      "!", "#", "$", "%", "&", "'", "*", "+", "-", ".", "^", "_", "|", "~"
    ];

    private array $headers = [];

    public function __construct(?bool $requestHeader = true)
    {
        if ($requestHeader) {
            $this->headers = $this->getHeaders();
        }
    }

    public static function canonicalHeaderKey(string $s): string
    {

        $s = strtolower($s);

        $separator = '';

        $containsValidSeparator = false;

        foreach (self::VALID_HEADER_SEPARATORS as $spr) {
            if (str_contains($s, $spr)) {
                $containsValidSeparator = true;
                $separator = $spr;
                break;
            }
        }

        if (!$containsValidSeparator) {
            return $s;
        }

        $keyParts = explode($separator, $s);

        $newKeyParts = [];

        foreach ($keyParts as $kp) {
            $newKeyParts[] = ucfirst($kp);
        }

        $headerKey = implode('-', $newKeyParts);

        return $headerKey;
    }

    private function getHeaders(): array
    {
        $reqHeaders = [];

        foreach ($_SERVER as $key => $v) {

            $isHttpPrefixed = str_starts_with($key, 'HTTP');

            if ($isHttpPrefixed
                || in_array(strtolower($key), self::COMMON_HEADERS)
            ) {

                $canonicKey = self::canonicalHeaderKey($key);

                if ($isHttpPrefixed) {
                    $headerKey = str_replace('Http-', '', $canonicKey);
                } else {
                    $headerKey = $canonicKey;
                }

                $reqHeaders[$headerKey][] = $v;
            }
        }

        return $reqHeaders;
    }

    public function add(string $key, string $value): void
    {
        $this->headers[$this->canonicalHeaderKey($key)][] = $value;
    }

    public function clone(): self
    {
        $headerClone = new self();
        $headerClone->headers = $this->headers;
        return $headerClone;
    }

    public function del(string $key): void
    {
        $this->headers[$this->canonicalHeaderKey($key)] = [];
    }

    public function get(string $key): string
    {
        return $this->headers[$this->canonicalHeaderKey($key)][0] ?? "";
    }

    public function set(string $key, string $value): void
    {
        $this->headers[$this->canonicalHeaderKey($key)] = [];
        $this->headers[$this->canonicalHeaderKey($key)][] = $value;
    }

    public function values(string $key): array
    {
        return $this->headers[$this->canonicalHeaderKey($key)];
    }

    public function entries(): array
    {
        return $this->headers;
    }
}
