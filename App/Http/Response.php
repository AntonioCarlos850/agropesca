<?php

namespace App\Http;

class Response {
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
    
    public function setHeader(string $key, $value){
        $this->header[$key] = $value;
    }

    public function setContentType(string $contentType){
        $this->contentType = $contentType;
        $this->setHeader('Content-Type', $contentType);
    }

    public function sendHeaders(){
        http_response_code($this->httpCode);

        foreach($this->headers as $key=>$value){
            header($key.": ".$value);
        }
    }

   public function send(){
       $this->sendHeaders();

       switch($this->contentType){
           case 'text/html': 
            echo $this->content;
            exit;
       }
   }

}