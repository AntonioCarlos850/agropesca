<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use App\Model\Entity\PostCategoryEntity;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use \App\Utils\View;
use Exception;

class Post {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPost(Request $request, int $id) :string {
        try {
            $userSessionInfo = LoginSession::getUserSession();
            $postEntity = PostEntity::getPostById($id);
            $postCategoryEntities = PostCategoryEntity::getCategories();

            if($postEntity->author->id != $userSessionInfo['id']){
                if($userSessionInfo['type_id'] == 3){
                    // $request->getRouter()->redirect("/painel/myPosts");
                    var_dump("a");
                    exit;
                } else {
                    var_dump("b");
                    exit;
                    // $request->getRouter()->redirect("/painel/myPosts");
                }
            }else{
                return Page::getPage($request, [
                    'content' => View::render('/Panel/post', [
                        'title' => $postEntity->title,
                        'description' => $postEntity->description,
                        'title' => $postEntity->title,
                        'body' => $postEntity->body,
                        'categories' => array_map(function(PostCategoryEntity $postCategoryEntity) use ($postEntity){
                            return View::render("/Components/UI/option", [
                                'value' => $postCategoryEntity->id,
                                'content' => $postCategoryEntity->name,
                                'selected' => $postEntity->category->id == $postCategoryEntity->id ? 'selected' : ''
                            ]);
                        }, $postCategoryEntities),
                    ]),
                ]);
            }
        } catch (Exception $exception){
            var_dump($exception->getMessage());
            exit;
            // $request->getRouter()->redirect("/painel/myPosts");
        }
    }
    
    public static function renderSideBar(Request $request, array $aditionalLinks = []) {
        return View::render('Components/Panel/sidebar', [
            'content' => array_map(function(array $param) use ($request) {
                return View::render('Components/Panel/sidebarItemLink', array_merge($param, [
                    'class' => $request->getUri() == $param['link'] ? 'active' : ''
                ]));
            }, array_merge([
                ['link' => '/painel/myPosts','content' => 'Meus Posts',],
                ['link' => '/painel/myProfile','content' => 'Meu Perfil',],
                ['link' => '/','content' => 'Voltar ao site',]
            ], $aditionalLinks))
        ]);
    }

    public static function renderCss(array $links){
        return array_map(function(string $link){
            View::render('Components/Post/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }

    public static function renderJs(array $links){
        return array_map(function (string $link){
            return View::render('Components/Post/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }
}