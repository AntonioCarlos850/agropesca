<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use App\Model\Entity\ImageEntity;
use App\Model\Entity\PostCategoryEntity;
use App\Model\Entity\PostEntity;
use App\Session\LoginSession;
use App\Utils\UploadImageUtils;
use \App\Utils\View;
use Exception;

class Post
{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPost(Request $request, $id): string
    {
        try {
            $postEntity = PostEntity::getPostById(intval($id));

            return self::renderPostPage($request, [
                'title' => $postEntity->title,
                'id' => $postEntity->id,
                'description' => $postEntity->description,
                'body' => $postEntity->body,
                'active' => $postEntity->active,
                'categoryId' => $postEntity->category->id,
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'actionPost' => 'Editar',
            ]);
        } catch (Exception $exception) {
            $request->getRouter()->redirect("/painel/myPosts");
        }
    }

    public static function getNewPost(Request $request): string
    {
        try {
            return self::renderNewPostPage($request);
        } catch (Exception $exception) {
            $request->getRouter()->redirect("/painel/myPosts");
        }
    }

    public static function editPost(Request $request, $id)
    {
        $postVars = $request->getPostVars();

        try {
            $postEntity = PostEntity::getPostById(intval($id));
            $postEntity->setTitle($postVars['title']);
            $postEntity->setDescription($postVars['description']);
            $postEntity->setBody($postVars['body']);
            $postEntity->setCategory(['category_id' => $postVars['category_id']]);
            $postEntity->setActive(isset($postVars['active']) && $postVars['active'] ? 1 : 0);

            $postEntity->update();

            return self::renderPostPage($request, [
                'actionPost' => 'Editar',
                'title' => $postEntity->title,
                'id' => $postEntity->id,
                'description' => $postEntity->description,
                'imageSrc' => $postEntity->getImageUri(),
                'imageAlt' => $postEntity->getImageAlt(),
                'body' => $postEntity->body,
                'active' => $postEntity->active,
                'title' => $postEntity->title,
                'categoryId' => $postEntity->category->id,
                'body' => $postVars['body'] ?? null,
                'description' => $postVars['description'] ?? null,
                'categoryId' => $postVars['category_id'] ?? null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => "Post editado com successo",
                    "divClass" => "success-message"
                ])
            ]);
        } catch (Exception $exception) {
            try{
                $postEntity = PostEntity::getPostById(intval($id));

                $imageSrc = $postEntity->getImageUri();
                $imageAlt = $postEntity->getImageAlt();
            } catch (Exception $exception2){
            }

            return self::renderPostPage($request, [
                'actionPost' => 'Editar',
                'title' => $postVars['title'] ?? null,
                'id' => $id,
                'body' => $postVars['body'] ? html_entity_decode($postVars['body']) : null,
                'active' => isset($postVars["active"]) && $postVars["active"] ? 'checked' : false,
                'description' => $postVars['description'] ?? null,
                'categoryId' => $postVars['category_id'] ?? null,
                'imageSrc' => $imageSrc ?? null,
                'imageAlt' => $imageAlt ?? null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    public static function createPost(Request $request)
    {
        $postVars = $request->getPostVars();
        $loginSessionData = LoginSession::getUserSession();

        try {
            if(!isset($postVars['category_id'])){
                throw new Exception("Necessária a escolha de uma categoria");
            }

            if(!isset($postVars['title'])){
                throw new Exception("Título necessário");
            }

            $postEntity = PostEntity::createPost([
                'title' => $postVars['title'] ?? '',
                'description' => $postVars['description'] ?? '',
                'body' => $postVars['body'] ?? '',
                'category_id' => $postVars['category_id'] ?? null,
                'author_id' => $loginSessionData['id'],
            ]);

            return $request->getRouter()->redirect("/painel/post/{$postEntity->id}");
        } catch (Exception $exception) {
            return self::renderNewPostPage($request, [
                'actionPost' => 'Criar',
                'title' => $postVars['title'] ?? null,
                'body' => $postVars['body'] ?? null,
                'description' => $postVars['description'] ?? null,
                'categoryId' => $postVars['category_id'] ?? null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    private static function renderNewPostPage(Request $request, array $params = [])
    {
        try {
            $postCategoryEntities = PostCategoryEntity::getCategories();
        } catch (Exception $exception) {
            $postCategoryEntities = [];
        }

        return Page::getPage($request, [
            'css' => ['/Resources/css/edit.css'],
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
            'content' => View::render('/Panel/createPost', [
                'id' => $params['id'] ?? null,
                'actionPost' => "Criar",
                'title' => $params['title'] ?? null,
                'description' => $params['description'] ?? null,
                'body' => $params['body'] ?? null,
                'message' => $params['message'] ?? null,
                'categories' => array_map(function (PostCategoryEntity $postCategoryEntity) {
                    return View::render("/Components/UI/option", [
                        'value' => $postCategoryEntity->id,
                        'content' => $postCategoryEntity->name,
                        'selected' => (isset($params['categoryId']) ? $params['categoryId'] == $postCategoryEntity->id : false) ? 'selected' : '',
                    ]);
                }, $postCategoryEntities),
            ]),
        ]);
    }

    private static function renderPostPage(Request $request, array $params = [])
    {
        try {
            $postCategoryEntities = PostCategoryEntity::getCategories();
        } catch (Exception $exception) {
            $postCategoryEntities = [];
        }

        return Page::getPage($request, [
            'css' => ['/Resources/css/edit.css'],
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
            'content' => View::render('/Panel/editPost', [
                'id' => $params['id'] ?? null,
                'actionPost' => "Editar",
                'title' => $params['title'] ?? null,
                'description' => $params['description'] ?? null,
                'body' => $params['body'] ? html_entity_decode($params['body']) : null,
                'message' => $params['message'] ?? null,
                'imageSrc' => $params["imageSrc"] ?? null,
                'imageAlt' => $params["imageAlt"] ?? null,
                'checked' => isset($params["active"]) && $params["active"] ? 'checked' : '',
                'categories' => array_map(function (PostCategoryEntity $postCategoryEntity) use ($params){
                    return View::render("/Components/UI/option", [
                        'value' => $postCategoryEntity->id,
                        'content' => $postCategoryEntity->name,
                        'selected' => $postCategoryEntity->id == ($params['categoryId'] ?? null) ? 'selected' : ''
                    ]);
                }, $postCategoryEntities),
            ]),
        ]);
    }

    public static function editImage(Request $request, $id)
    {
        try {
            $uploadedImage = UploadImageUtils::getImageByField('image');
            $postEntity = PostEntity::getPostById($id);
            $postVars = $request->getPostVars();

            if ($uploadedImage) {
                if ($postEntity->image) {
                    $postEntity->image->delete();
                }

                $imageEntity = ImageEntity::createImage([
                    'path' => $uploadedImage->dir,
                    'filename' => $uploadedImage->filename,
                    'alt' => $postVars['alt'] ?? ''
                ]);

                $postEntity->setImage($imageEntity);

                $postEntity->update();
            }

            if (isset($postVars['alt']) && $postEntity->image) {
                $postEntity->image->setAlt($postVars['alt']);
                $postEntity->image->update();
            }
        } catch (Exception $exception) {
        }

        $request->getRouter()->redirect("/painel/post/{$id}");
    }

    public static function deletePost(Request $request, $id)
    {
        try {
            $postEntity = PostEntity::getPostById($id);
            $postEntity->delete();
            $request->getRouter()->redirect("/painel/myPosts");
        } catch (Exception $exception) {
            $request->getRouter()->redirect("/painel/myPosts");
        }
    }
}
