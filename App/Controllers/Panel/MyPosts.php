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
            $postEntities = PostEntity::getPostsByAuthor($userSessionData['id']);

            return Page::getPage($request, [
                'content' => View::render("/Panel/myPosts", [
                    'posts' => self::renderPosts($postEntities),
                ])
            ]);
        } catch (Exception $exception){
            var_dump($exception->getMessage());
            exit;
            // $request->getRouter()->redirect("/painel/myPosts");
        }
    }

    public static function renderPosts(array $postEntities){
        return array_map(function(PostEntity $postEntity){
            return View::render('Components/Panel/post', [
                "title" => $postEntity->title,
                "editLink" => "/painel/post/{$postEntity->id}",
                "deleteLink" => null,
                "imageSrc" => null,
                "imageAlt" => null,
            ]);
        }, $postEntities);
    }
}