<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue;

class Router
{
    private $url;
    private $prefix = "";
    private $routes = [];
    private $request;

    public function __construct(string $url)
    {
        $this->request = new Request($this);
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
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    return $methods[$httpMethod];
                } else {
                    throw new Exception("Método não permitido", 405);
                }
            }
        }

        if(strpos($uri, 'painel')){
            $this->redirect("/painel/");
        }else{
            $this->redirect("/");
        }
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

        $params["middlewares"] = $params["middlewares"] ?? [];

        $params['variables'] = [];
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }


        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    public function get(string $route, Closure $controller, array $middlewares = []): void
    {
        $this->addRoute('GET', $route, [
            'middlewares' => $middlewares,
            $controller
        ]);
    }

    public function post(string $route, Closure $controller, array $middlewares = []): void
    {
        $this->addRoute('POST', $route, [
            'middlewares' => $middlewares,
            $controller
        ]);
    }

    public function put(string $route, Closure $controller, array $middlewares = []): void
    {
        $this->addRoute('PUT', $route, [
            'middlewares' => $middlewares,
            $controller
        ]);
    }

    public function delete(string $route, Closure $controller, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $route, [
            'middlewares' => $middlewares,
            $controller
        ]);
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
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return (new Queue($route["middlewares"], $route["controller"], $args))->next($this->request);
        } catch (Exception $e) {
            return (new Response($e->getMessage(), $e->getCode()));
        }
    }

    public function getCurrentUrl(): string
    {
        return $this->url . $this->getUri();
    }

    public function redirect($route): void
    {
        $url = $this->url . $route;
        header('location: ' . $url);
        exit;
    }
}
