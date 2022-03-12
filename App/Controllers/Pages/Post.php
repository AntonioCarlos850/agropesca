<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use App\Model\Entity\PostEntity;
use App\Session\Login;
use \App\Utils\View;
use Exception;

class Post extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getPost($postSlug, Request $request) :string {
        try {
            $postEntity = PostEntity::getPostBySlug($postSlug);

            return Page::getPage([
                "title" => $postEntity->title,
                "content" => View::render("pages/post", [
                    "title" => $postEntity->title,
                    "body" => $postEntity->body,
                    "date" => $postEntity->creation_date,
                    "time" => $postEntity->creation_date,
                    "imageSrc" => null,
                    "imageAlt" => null,
                    "authorImageSrc" => null,
                    "authorImageAlt" => null,
                    "authorDescription" => $postEntity->author->description,
                    "authorPageSrc" => $postEntity->author->slug,
                    "authorName" => $postEntity->author->name,
                    "mostViewsPosts" => null,
                ])
            ]);

        } catch (Exception $error){
            $request->getRouter()->redirect("/");
        }
        
    }

    private static function renderArticles(array $postEntityes)
    {
        
    }
}