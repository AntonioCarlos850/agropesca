<?php

namespace App\Http;

class Response
{
    private int $httpCode;
    private array $headers = [];
    private string $contentType;
    private $content;

    public function __construct($content, int $httpCode = 200, string $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Cria ou edita uma posição dos headers
     */
    public function setHeader(string $key, $value): void
    {
        $this->header[$key] = $value;
    }

    /**
     * Altera o ContentType na classe e nos headers
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->setHeader('Content-Type', $contentType);
    }

    private function sendHeaders(): void
    {
        http_response_code($this->httpCode);

        foreach ($this->headers as $key => $value) {
            header($key . ": " . $value);
        }
    }

    /**
     * Envia os headers e o conteúdo dependendo do contentType
     */
    public function send(): void
    {
        $this->sendHeaders();

        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
        }

        exit;
    }
}
