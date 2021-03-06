<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use App\Session\SearchSession;
use App\Utils\Helpers;
use \App\Utils\View;
use Exception;

class Busca extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getBusca(Request $request) :string {
        $queryParams = $request->getQueryParams();
        self::setSearchSessionByQueryParams($queryParams);

        $searchSessionData = SearchSession::getSearchSession();

        try {
            $postsQuantity = PostEntity::getPostsCountBySearch($searchSessionData['conditions']);

            $searchPostEntities = PostEntity::getPostsBySearch(
                $searchSessionData['conditions'], 
                $searchSessionData['orders'], 
                $searchSessionData['itensPerPage'], 
                self::getSearchPageOffset($searchSessionData)
            );

            $mostViewdPostEntities = PostEntity::getActivePosts(["blg_post.visits DESC"],[],3);
            $weekPostEntities = PostEntity::getActivePosts(["blg_post.creation_date DESC"],[],3);
        } catch (Exception $exception) {
            $searchPostEntities = [];
            $mostViewdPostEntities = [];
            $weekPostEntities = [];
            $postsQuantity = 0;
        }


        $totalPages = ceil($postsQuantity / $searchSessionData['itensPerPage']);

        if( !isset($queryParams['search']) && isset($searchSessionData['search']) && $searchSessionData['search']){
            $queryParams['search'] = $searchSessionData['search'];
        }

        $next = false;
        $nextParams = $queryParams;

        if(!isset($nextParams['page'])){
            $nextParams['page'] = 1;
        }

        if($nextParams['page'] < $totalPages){
            $next = true;
            $nextParams['page'] = $nextParams['page'] + 1;
        }

        return Page::getPage([
            "title" => "Agroblog | Busca",
            'navbar' => true,
            'css' => ['/Resources/css/busca.css','/Resources/css/most_views.css','/Resources/css/pagination.css'],
            "content" => View::render("pages/busca", [
                "qtd" => $postsQuantity,
                "search" => $queryParams['search'] ?? null,
                "searchPosts" => self::renderPosts($searchPostEntities),
                "mostViewsPosts" => self::renderMostViwedPosts($mostViewdPostEntities),
                "weekPost" => self::renderWeekPosts($weekPostEntities),
                "dropdownOptions" => self::renderOrderDropdownOptions($searchSessionData),
                "extraOrderFields" => self::extraFields($searchSessionData),
                "searchAuthor" => null,
                "searchCategory" => null,
                'pagination' => count($searchPostEntities) < $postsQuantity ? View::render('Components/Page/pagination', [    
                    'links' => self::getPaginationLinks($searchSessionData['page'],$postsQuantity, $searchSessionData['itensPerPage'], $queryParams),
                    'class' => null,
                    'next' => $next ? View::render('Components/Page/nextLink', [
                        'link' => "/busca".Helpers::contructQueryParams($nextParams),
                    ]) : []
                ]) : null
            ])
        ]);
    }

    public static function setSearchSessionByQueryParams(array $queryParams = []){
        SearchSession::setSearchSession([
            'orders' => isset($queryParams['order']) ? [$queryParams['order']] : null,
            'search' => $queryParams['search'] ?? null,
            'author' => $queryParams['author'] ?? null,
            'category' => $queryParams['category'] ?? null,
            'page' => $queryParams['page'] ?? 1
        ]);
    }

    public static function getPaginationLinks($actualPage, $totalQuantity, $postsPerPage, $paginationQueryParams){
        $totalPages = ceil($totalQuantity / $postsPerPage);

        $params = [];
        for( $i = 1; $i <= $totalPages; $i++){
            $paginationQueryParams['page'] = $i;
            $params[] = [
                'link' => "/busca".Helpers::contructQueryParams($paginationQueryParams),
                'class' => $i == $actualPage ? 'active' : null,
                'number' => $i
            ];
        }

        return array_map(function($param){
            return View::render('Components/Page/paginationLink', $param);
        }, $params);
    }

    public static function getSearchPageOffset(array $searchSessionData)
    {
        if(isset($searchSessionData['page'])){
            return (($searchSessionData['page'] -1) * $searchSessionData['itensPerPage']);
        }else{
            return 0;
        }
    }

    public static function renderOrderDropdownOptions(array $searchSessionData)
    {
        return array_map(function ($item) use ($searchSessionData){
            return View::render('Components/UI/option', [
                'content' => $item['text'],
                'value' => $item['value'],
                'selected' => $item['value'] == $searchSessionData['orders'][0] ? 'selected' : null,
            ]);
        }, [
            [
                'text' => 'Relevância',
                'value' => 'relevancia',
            ],
            [
                'text' => 'Recente',
                'value' => 'recente',
            ],
            [
                'text' => 'Antigo',
                'value' => 'antigo',
            ],
        ]);
    }

    public static function renderPosts(array $postEntities = []) {
        return array_map(function (PostEntity $postEntity){
            return View::render('/Components/Page/post', [
                'link' => "/post/{$postEntity->slug}",
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'title' => $postEntity->title,
                'description' => $postEntity->description,
                'authorName' => $postEntity->author->name,
                'date' => $postEntity->creation_date->format("d/m/Y"),
            ]);
        }, $postEntities);
    }
    public static function renderMostViwedPosts(array $postEntities = []) {
        return array_map(function (PostEntity $postEntity){
            return View::render('/Components/Page/mostViewPost', [
                'link' => "/post/{$postEntity->slug}",
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'title' => $postEntity->title,
            ]);
        }, $postEntities);
    }
    public static function extraFields(array $searchSessionData = []) {
        $extraLinks = [];
        foreach($searchSessionData['conditions'] as $key => $value){
            if($value){
                $extraLinks[] = View::render('/Components/Page/extraOrderFields', [
                    'name' => $key,
                    'value' => $value,
                ]);
            }
        }
        return $extraLinks;
    }
    public static function renderWeekPosts(array $postEntities = []) {
        return array_map(function (PostEntity $postEntity){
            return View::render('/Components/Page/weekPost', [
                'link' => "/post/{$postEntity->slug}",
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'title' => $postEntity->title,
                'description' => $postEntity->description,
            ]);
        }, $postEntities);
    }
}