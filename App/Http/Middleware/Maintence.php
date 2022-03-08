<?php

namespace App\Http\Middleware;

use App\Http\Request;
use Exception;

class Maintence implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        if(getenv("MAINTENCE") == 'true'){
            throw new Exception("Sistema em manutenção. Tente novamente mais tarde", 200);
        }
        return $next($request);
    }
}