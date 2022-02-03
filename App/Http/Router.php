<?php

namespace App\Http;

use \Closure;
use \Exception;

class Router {
    private $url;
    private $prefix = "";
    private $routes = [];
    private $request;

    public function __construct(string $url)
    {   
        $this->request = new Request();
        $this->url = $url;
        $this->prefix = $this->setPrefix();
    }

    private function setPrefix(){
        $parsedUrl = parse_url($this->url);
        $this->prefix = $parsedUrl["path"] ?? '';
    }

    private function getUri(): string{
        $uri = $this->request->getUri();
        return end(strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri]);
    }

    private function getRoute(){
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();

        // Continuar AQui------

    }
    
    public function addRoute(string $method, string $route, array $params = []){
        foreach($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    public function get(string $route, array $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    public function post(string $route, array $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    public function put(string $route, array $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete(string $route, array $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    public function run(){
        try {
            $route = $this->getRoute();
        } catch (Exception $e){
            (new Response($e->getMessage(), $e->getCode()))->send();
        }
    }
}