<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\LoginSession;
use Exception;

class RequireLogout implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        if(LoginSession::isLogged()){
            $request->getRouter()->redirect("/");
        }

        return $next($request);
    }
}