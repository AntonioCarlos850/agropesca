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
            $mostViewedPostsEntities = PostEntity::getActivePosts(['blg_post.visits DESC', 'blg_post.id != '.$postEntity->id],[],4);

            return Page::getPage([
                'title' => $postEntity->title,
                'navbar' => true,
                'css' => ['/Resources/css/post.css','/Resources/css/most_views.css','/Resources/css/autor_div.css'],
                'content' => View::render('pages/post', [
                    'title' => $postEntity->title,
                    'body' => $postEntity->body,
                    'date' => $postEntity->creation_date->format('d/m/Y'),
                    'time' => $postEntity->creation_date->format('H:i'),
                    'imageSrc' => $postEntity->image ? $postEntity->image->getUri() : null,
                    'imageAlt' => $postEntity->image ? $postEntity->image->alt : null,
                    'authorImageSrc' => $postEntity->author->image ? $postEntity->author->image->getUri() : null,
                    'authorImageAlt' => $postEntity->author->image ? $postEntity->author->image->alt : null,
                    'authorDescription' => $postEntity->author->description,
                    'authorPageSrc' => $postEntity->author->slug,
                    'authorName' => $postEntity->author->name,
                    'mostViewedPosts' => self::renderAuthorPosts($mostViewedPostsEntities),
                ])
            ]);

        } catch (Exception $error){
            $request->getRouter()->redirect('/');
        }
        
    }

    private static function renderAuthorPosts(array $postEntities) : array
    {
        return array_map(function(PostEntity $postEntity){
            return View::render('Components/Page/mostViewPost', [
                'link' => '/post/'.$postEntity->slug,
                'title' => $postEntity->title,
                'description' => $postEntity->description,
                'imageSrc' => $postEntity->image ? $postEntity->image->getUri() : null,
                'imageAlt' => $postEntity->image ? $postEntity->image->alt : null,
                'authorName' => $postEntity->author->name,
                'date' => $postEntity->creation_date->format('d/m/Y')
            ]);
        }, $postEntities);
    }
}