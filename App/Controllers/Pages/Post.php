<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use App\Model\Entity\PostEntity;
use \App\Utils\View;
use Exception;

class Post extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getPost($postSlug, Request $request) :string {
        try {
            $postEntity = PostEntity::getPostBySlug($postSlug);
            $authorMostViewedPostsEntities = PostEntity::getPostsByAuthor($postEntity->author->id, ["blg_post.visits DESC", "blg_post.id != ".$postEntity->id]);

            return Page::getPage([
                "title" => $postEntity->title,
                'headScripts' => Page::renderJs(['https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', '/Resources/js/navbar.js']),
                'header' => Page::renderNavbar(),
                "content" => View::render("pages/post", [
                    "title" => $postEntity->title,
                    "body" => $postEntity->body,
                    "date" => $postEntity->creation_date->format("d/m/Y"),
                    "time" => $postEntity->creation_date->format("H:i"),
                    "imageSrc" => null,
                    "imageAlt" => null,
                    "authorImageSrc" => null,
                    "authorImageAlt" => null,
                    "authorDescription" => $postEntity->author->description,
                    "authorPageSrc" => $postEntity->author->slug,
                    "authorName" => $postEntity->author->name,
                    "mostViewedPosts" => self::renderAuthorPosts($authorMostViewedPostsEntities),
                ])
            ]);

        } catch (Exception $error){
            $request->getRouter()->redirect("/");
        }
        
    }

    private static function renderAuthorPosts(array $postEntities) : array
    {
        return array_map(function(PostEntity $postEntity){
            return View::render("Components/Page/post", [
                "link" => "/post/".$postEntity->slug,
                "title" => $postEntity->title,
                "description" => $postEntity->description,
                "imageSrc" => "https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg",
                "imageAlt" => "",
                "authorName" => $postEntity->author->name,
                "date" => $postEntity->creation_date->format("d/m/Y")
            ]);
        }, $postEntities);
    }
}