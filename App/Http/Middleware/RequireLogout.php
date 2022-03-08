<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\Login;
use Exception;

class RequireLogout implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        if(Login::isLogged()){
            $request->getRouter()->redirect("/");
        }

        return $next($request);
    }
}