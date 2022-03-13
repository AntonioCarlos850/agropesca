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
                $request->getRouter()->redirect("/painel/myPosts");
                // if($userSessionInfo['type_id'] == 3){
                //     // $request->getRouter()->redirect("/painel/myPosts");
                //     var_dump("a");
                //     exit;
                // } else {
                //     var_dump("b");
                //     exit;
                // }
            }else{
                return Page::getPage($request, [
                    'headScripts' => [
                        [
                            'src' => 'https://cdn.tiny.cloud/1/1652xpwe98k7npczrjkxeixgklizyog95zbe3svy7zdtua1f/tinymce/5/tinymce.min.js', 
                            'referrerpolicy' => 'origin'
                        ], 
                    ],
                    'endBodyScripts' => [
                        [
                            'content' => "tinymce.init({
                                selector: 'textarea',
                                plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
                                toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
                                toolbar_mode: 'floating',
                                tinycomments_mode: 'embedded',
                                tinycomments_author: 'Author name',
                            });
                            "
                        ]
                    ],
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
}