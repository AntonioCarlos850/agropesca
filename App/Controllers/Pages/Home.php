<?php

namespace App\Controllers\Pages;

use App\Model\Entity\PostCategoryEntity;
use App\Model\Entity\PostEntity;
use \App\Utils\View;
use Exception;

class Home extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getHome() :string {
        try {
            $mostViewedPostsEntities = PostEntity::getActivePosts(["blg_post.visits DESC"], [], 3);
            $recentPostsEntities = PostEntity::getActivePosts(['blg_post.creation_date DESC'], [], 6);
            $olderPostsEntities = PostEntity::getActivePosts(['blg_post.creation_date ASC'], [], 6);
            $categoriesEntities = PostCategoryEntity::getCategories(4);
        } catch (Exception $exception){
            $mostViewedPostsEntities = [];
            $recentPostsEntities = [];
            $olderPostsEntities = [];
            $categoriesEntities = [];
        }

        return Page::getPage([
            'title' => 'Agroblog | Home',
            'css' => ['/Resources/css/home.css','/Resources/css/most_views.css'],
            'navbar' => true,
            'content' => View::render('pages/home', [
                'mostViewsPosts' => self::renderMostViewPosts($mostViewedPostsEntities),
                'ourPosts' => self::renderPosts($recentPostsEntities),
                'postSuggestions' => self::renderPosts($olderPostsEntities),
                'categories' => self::renderCategories($categoriesEntities)
            ]),
        ]);
    }

    private static function renderMostViewPosts(array $postEntities)
    {
        return array_map(function(PostEntity $postEntity){
            return View::render('Components/Page/mostViewPost', [
                'link' => "/post/{$postEntity->slug}",
                'title' => $postEntity->title,
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
            ]);
        }, $postEntities);
    }

    private static function renderPosts(array $postEntities)
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

    private static function renderCategories(array $categoryEntities) : array
    {
        return array_map(function(PostCategoryEntity $categoryEntity){
            return View::render('Components/Page/category', [
                'link' => "/busca?category={$categoryEntity->id}",
                'imageSrc' => $categoryEntity->getImageUri(),
                'imageAlt' => $categoryEntity->name,
                'name' => $categoryEntity->name
            ]);
        }, $categoryEntities);
    }
}