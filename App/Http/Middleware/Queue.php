<?php
namespace App\Http\Middleware;

use Closure;
use Exception;

class Queue {
    private array $middlewares = [];
    private Closure $controller;
    private $controllerArgs = [];
    private static array $map = [];
    private static array $default = [];

    public function __construct(array $middlewares, Closure $controller, array $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public function next($request)
    {
        if(empty($this->middlewares)){
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        $middleware = array_shift($this->middlewares);
        
        if(!isset(self::$map[$middleware])){
            throw new Exception("Problemas ao executar o middleware da requisição", 500);
        }

        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };
        return (new self::$map[$middleware])->handle($request, $next);
    }

    public static function setMap(array $map){
        self::$map = $map;
    }

    public static function setDefault(array $default){
        self::$default = $default;
    }

}