<?php

namespace Royers\Http;

class Response implements ResponseWriter
{
    private Header $header;
    private bool $wereHeadersSent = false;

    public function header(): Header
    {
        if (!isset($this->header)) {
            $this->header = new Header(false);
        }

        return $this->header;
    }

    public function writeHeader(StatusCode $code): void
    {
        foreach ($this->header->entries() as $headerKey => $headerValues) {
            if (count($headerValues) == 1) {
                header("{$headerKey}: {$headerValues[0]}");
            } elseif (count($headerValues)  > 1) {
                header("{$headerKey}: ". implode(',', $headerValues));
            }
        }

        http_response_code($code->value);
        $this->wereHeadersSent = true;
    }

    private function checkContentType(string $content): string
    {
        //TODO DETECT Content-Type of the response body
    }

    public function write(string $content)
    {

        $contentType = $this->checkContentType($content);

        if (!isset($this->header['Content-Type'])) {
            header('Content-Type : ' . $contentType);
        }

        if (!$this->wereHeadersSent) {
            $this->writeHeader(StatusCode::Ok);
        }

        echo $content;
    }
}
