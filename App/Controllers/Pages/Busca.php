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
        SearchSession::setSearchSession($queryParams);
        $searchSessionData = SearchSession::getSearchSession();
        try {
            $postsQuantity = PostEntity::getActivePostCount(self::getSearchOrder($searchSessionData), self::getSearchAditionalCondition($searchSessionData), self::getSearchParameters($searchSessionData));

            $searchPostEntities = PostEntity::getActivePosts(
                self::getSearchOrder($searchSessionData),
                self::getSearchAditionalCondition($searchSessionData), 
                $searchSessionData['itensPerPage'],
                self::getSearchPageOffset($searchSessionData),
                self::getSearchParameters($searchSessionData)
            );

            $mostViewdPostEntities = PostEntity::getActivePosts(["blg_post.visits DESC"],[],3);
            $weekPostEntities = PostEntity::getActivePosts(["blg_post.creation_date DESC"],[],1);
        } catch (Exception $exception) {
            $searchPostEntities = [];
            $mostViewdPostEntities = [];
            $weekPostEntities = [];
            $postsQuantity = 0;
        }

        $paginationQueryParams = $queryParams;
        $paginationQueryParams["page"] = isset($paginationQueryParams["page"]) ? ($paginationQueryParams["page"] + 1) : 2; 

        return Page::getPage([
            "title" => "Agroblog | Busca",
            'navbar' => true,
            'css' => ['/Resources/css/most_views.css'],
            "content" => View::render("pages/busca", [
                "qtd" => $postsQuantity,
                "search" => $queryParams['search'] ?? null,
                "searchPosts" => self::renderPosts($searchPostEntities),
                "mostViewsPosts" => self::renderMostViwedPosts($mostViewdPostEntities),
                "weekPost" => self::renderWeekPosts($weekPostEntities),
                "dropdownOptions" => self::renderOrderDropdownOptions($searchSessionData),
                'pagination' => count($searchPostEntities) < $postsQuantity ? View::render('Components/Page/pagination', [    
                    'links' => self::getPaginationLinks($searchSessionData['page'],$postsQuantity, $searchSessionData['itensPerPage'], $queryParams),
                    'class' => null,
                    'nextLink' => "/busca".Helpers::contructQueryParams($paginationQueryParams)
                ]) : null
            ])
        ]);
    }

    public static function getPaginationLinks($actualPage, $totalQuantity, $postsPerPage, $paginationQueryParams){
        $totalPages = ceil($totalQuantity / $postsPerPage);

        $params = [];
        for($i = ($actualPage - 2); $i++; $i <= ($actualPage + 2)){
            if($i > 0 && $i <= $totalPages){
                $paginationQueryParams['page'] = $i;
                $params[] = [
                    'link' => "/busca".Helpers::contructQueryParams($paginationQueryParams),
                    'class' => $i == $actualPage ? 'active' : null,
                    'number' => $i
                ];
            }
        }

        return array_map(function($param){
            return View::render('Components/Page/paginationLink', $param);
        }, $params);
    }

    public static function getSearchAditionalCondition(array $searchSessionData)
    {
        if(isset($searchSessionData['search'])){
            return ["blg_post.title LIKE CONCAT('%', :search ,'%')"];
        }else{
            return [];
        }
    }

    public static function getSearchParameters(array $searchSessionData)
    {
        $parameters = [];
        if(isset($searchSessionData['search'])){
            $parameters['search'] = $searchSessionData['search'];
        }

        return $parameters;
    }

    public static function getSearchOrder(array $searchSessionData)
    {
        if(isset($searchSessionData['order'])){
            switch ($searchSessionData['order']){
                case 'relevancia':
                    return ['blg_post.visits DESC'];
                    break;
                case 'recente':
                    return ['blg_post.creation_date DESC'];
                    break;
                case 'antigo':
                    return ['blg_post.creation_date ASC'];
                    break;
                default:
                    return [];
            }
        }else{
            return [];
        }
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
                'selected' => $item['value'] == $searchSessionData['order'] ? 'selected' : null,
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
                'imageSrc' => null,
                'imageAlt' => null,
                'title' => $postEntity->title,
                'description' => $postEntity->description,
                'authorName' => $postEntity->author->id,
                'date' => $postEntity->creation_date->format("d/m/Y"),
            ]);
        }, $postEntities);
    }
    public static function renderMostViwedPosts(array $postEntities = []) {
        return array_map(function (PostEntity $postEntity){
            return View::render('/Components/Page/mostViewPost', [
                'link' => "/post/{$postEntity->slug}",
                'imageSrc' => 'https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg',
                'imageAlt' => null,
                'title' => $postEntity->title,
            ]);
        }, $postEntities);
    }
    public static function renderWeekPosts(array $postEntities = []) {
        return array_map(function (PostEntity $postEntity){
            return View::render('/Components/Page/post', [
                'link' => "/post/{$postEntity->slug}",
                'imageSrc' => 'https://www.tenhomaisdiscosqueamigos.com/wp-content/uploads/2019/09/tyler-the-creator-1.jpg',
                'imageAlt' => null,
                'title' => $postEntity->title,
                'description' => $postEntity->description,
            ]);
        }, $postEntities);
    }
}