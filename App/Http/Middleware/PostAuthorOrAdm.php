<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use Exception;

class PostAuthorOrAdm implements MiddlewareInterface{
    public function handle(Request $request, $next) {
        try {
            $uriParams = $request->getRouter()->getUriParams();
            if(!isset($uriParams['id'])){
                $request->getRouter()->redirect("/painel/myPosts");
            }

            $userSessionData = LoginSession::getUserSession();

            $postEntity = PostEntity::getPostById($uriParams['id']);

            if($userSessionData['type_id'] == 3 || $userSessionData['id'] == $postEntity->author->id){
                return $next($request);
            }else{
                $request->getRouter()->redirect("/painel/myPosts");
            }
        } catch (Exception $exception){
            $request->getRouter()->redirect("/painel/myPosts");
        }

    }
}