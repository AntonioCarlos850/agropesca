<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
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

    private function setPrefix(): void
    {
        $parsedUrl = parse_url($this->url);
        $this->prefix = $parsedUrl["path"] ?? '';
    }

    private function getUri(): string
    {
        $uri = $this->request->getUri();

        $parsedUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($parsedUri);
    }

    private function getRoute(): array
    {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['request'] = $this->request;

                    return $methods[$httpMethod];
                } else {
                    throw new Exception("Método não permitido", 405);
                }
            }
        }

        throw new Exception("URL não encontrada", 404);
    }

    public function addRoute(string $method, string $route, array $params = []): void
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }


        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    public function get(string $route, array $params = []): void
    {
        $this->addRoute('GET', $route, $params);
    }

    public function post(string $route, array $params = []): void
    {
        $this->addRoute('POST', $route, $params);
    }

    public function put(string $route, array $params = []): void
    {
        $this->addRoute('PUT', $route, $params);
    }

    public function delete(string $route, array $params = []): void
    {
        $this->addRoute('DELETE', $route, $params);
    }

    public function run(): Response
    {
        try {
            $route = $this->getRoute();

            if (!isset($route["controller"])) {
                throw new Exception("URL não pode ser processada", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return (new Response($e->getMessage(), $e->getCode()));
        }
    }
}
