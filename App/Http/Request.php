<?php

namespace App\Http;

class Request{
    private string $httpMethod;
    private string $uri;
    private array $queryParams ;
    private array $postVars;
    private array $headers;
    private Router $router;

    public function __construct(Router $router)
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? '';
        $this->setUri();
        $this->router = $router;
    }

    private function setUri(){
        $this->uri = $_SERVER["REQUEST_URI"] ? explode("?",$_SERVER["REQUEST_URI"])[0] : '';
    }

    public function getHttpMethod():string{
        return $this->httpMethod;
    }

    public function getUri():string{
        return $this->uri;
    }

    public function getQueryParams():array{
        return $this->queryParams;
    }

    public function getPostVars():array{
        return $this->postVars;
    }

    public function getHeaders():array{
        return $this->headers;
    }

    public function getRouter():Router{
        return $this->router;
    }
}
