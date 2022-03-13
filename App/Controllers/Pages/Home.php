<?php

namespace App\Controllers\Pages;

use App\Model\Entity\PostCategoryEntity;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use \App\Utils\View;
use Exception;

class Home extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getHome() :string {
        try {
            $postEntities = PostEntity::getActivePosts();
            $categoriesEntities = PostCategoryEntity::getCategories();
        } catch (Exception $exception){
            $postEntities = [];
            $categoriesEntities = [];
        }

        return Page::getPage([
            'title' => 'Agroblog | Home',
            'css' => Page::renderCss(['/Resources/css/global.css','/Resources/css/home.css','/Resources/css/most_views.css']),
            'headScripts' => Page::renderJs(['https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', '/Resources/js/navbar.js']),
            'header' => Page::renderNavbar(),
            'content' => View::render('pages/home', [
                'mostViewsPosts' => self::renderMostViewPosts($postEntities),
                'postSuggestions' => self::renderPosts($postEntities),
                'ourPosts' => self::renderPosts($postEntities),
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
                'imageSrc' => 'https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg',
                'imageAlt' => ''
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
                'imageSrc' => 'https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg',
                'imageAlt' => '',
                'authorName' => $postEntity->author->name,
                'date' => $postEntity->creation_date->format('d/m/Y')
            ]);
        }, $postEntities);
    }

    private static function renderCategories(array $categoryEntities) : array
    {
        return array_map(function(PostCategoryEntity $categoryEntity){
            return View::render('Components/Page/category', [
                'link' => '',
                'link' => '',
                'imageSrc' => 'https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg',
                'imageAlt' => '',
                'name' => $categoryEntity->name
            ]);
        }, $categoryEntities);
    }
}