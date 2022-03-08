<?php

namespace App\Http\Middleware;

use Exception;

class Maintence implements MiddlewareInterface{
    public function handle($request, $next) {
        if(getenv("MAINTENCE") == 'true'){
            throw new Exception("Sistema em manutenção. Tente novamente mais tarde", 200);
        }
        return $next($request);
    }
}