<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use \App\Utils\View;
use Exception;

class MyPosts {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getMyPosts(Request $request) :string {
        try {
            $userSessionData = LoginSession::getUserSession();
            
            if($userSessionData['type_id'] <= 2){
                $postEntities = PostEntity::getPostsByAuthor($userSessionData['id']);
            }else{
                $postEntities = PostEntity::getPosts();
            }

            return Page::getPage($request, [
                'css'=> ['/Resources/css/posts.css'],
                'content' => View::render("/Panel/myPosts", [
                    'posts' => self::renderPosts($postEntities),
                ])
            ]);
        } catch (Exception $exception){
            $request->getRouter()->redirect("/painel/myPosts");
        }
    }

    public static function renderPosts(array $postEntities){
        return array_map(function(PostEntity $postEntity){
            return View::render('Components/Panel/post', [
                'title' => $postEntity->title,
                'editLink' => "/painel/post/{$postEntity->id}",
                'deleteLink' => "/painel/post/{$postEntity->id}/delete",
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
            ]);
        }, $postEntities);
    }
}