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

    public function __construct()
    {
        $headers = getallheaders();
        $this->headers = $headers != false ? $headers : $this->getHeaders();
    }

    public static function canonicalHeaderKey(string $s): string
    {
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

        foreach ($_SERVER as $k => $v) {
            $key = strtolower($k);
            $isHttpPrefixed = str_starts_with($key, 'http');

            if ($isHttpPrefixed
                || in_array($key, self::COMMON_HEADERS)
            ) {

                $canonicKey = self::canonicalHeaderKey($key);

                if ($isHttpPrefixed) {
                    $headerKey = str_replace('Http-', '', $canonicKey);
                } else {
                    $headerKey = $canonicKey;
                }

                $reqHeaders[$headerKey] = $v;
            }
        }

        return $reqHeaders;
    }

    public function add(string $key, string $value): void
    {

    }

    public function clone(): self
    {
        return new self();
    }

    public function del(string $key): void
    {

    }

    public function get(string $key): string
    {
        return '';
    }

    public function set(string $key, string $value): void
    {

    }

    public function values(string $key): array
    {
        return [];
    }

}
