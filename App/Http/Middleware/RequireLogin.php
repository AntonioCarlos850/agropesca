<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\LoginSession;
use Exception;

class RequireLogin implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        if(!LoginSession::isLogged()){
            $request->getRouter()->redirect("/login");
        }
        
        return $next($request);
    }
}