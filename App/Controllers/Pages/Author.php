<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use App\Model\Entity\AuthorEntity;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use \App\Utils\View;
use Exception;

class Author extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getAuthor(Request $request, $authorSlug) :string {
        try {
            $authorEntity = AuthorEntity::getAuthorBySlug($authorSlug);
            $lastAuthorPosts = PostEntity::getPostsBySearch(["author" => $authorEntity->id], ["relevancia"], 4);

            return Page::getPage([
                'title' => "{$authorEntity->name} | Agroblog",
                'css' => ['/Resources/css/autor.css'],
                'content' => View::render('pages/author', [
                    'name' => $authorEntity->name,
                    'description' => $authorEntity->description,
                    'imageSrc' => $authorEntity->getImageUri(),
                    'imageAlt' => $authorEntity->getImageAlt(),
                    'lastPosts' => self::renderAuthorPosts($lastAuthorPosts)
                ])
            ]);

        } catch (Exception $error){
            $request->getRouter()->redirect('/');
        }
        
    }

    private static function renderAuthorPosts(array $postEntities) : array
    {
        return array_map(function(PostEntity $postEntity){
            return View::render('Components/Page/post', [
                'link' => '/post/'.$postEntity->slug,
                'title' => $postEntity->title,
                'description' => $postEntity->description,
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'authorName' => $postEntity->author->name,
                'date' => $postEntity->creation_date->format('d/m/Y')
            ]);
        }, $postEntities);
    }
}