<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Session\LoginSession;

class RequireAuthorType implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        $userSessionData = LoginSession::getUserSession();
        if($userSessionData['type_id'] < 2){
            $request->getRouter()->redirect("/painel/myProfile");
        }

        return $next($request);
    }
}