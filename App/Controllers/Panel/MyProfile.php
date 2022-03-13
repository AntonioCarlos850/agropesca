<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use App\Model\Entity\AuthorEntity;
use App\Model\Entity\PostEntity;
use App\Model\Entity\UserEntity;
use App\Session\LoginSession;
use \App\Utils\View;
use Exception;

class MyProfile {
    public static function getMyProfile(Request $request) : string {
        $userSessionData = LoginSession::getUserSession();

        if($userSessionData['type_id'] == 1){
            return self::getUserMyProfile($request);
        }else{
            return self::getAuthorMyProfile($request);
        }
    }

    public static function postMyProfile(Request $request) : string {
        $userSessionData = LoginSession::getUserSession();

        if($userSessionData['type_id'] == 1){
            return self::createAuthor($request);
        }else{
            return self::updateAuthor($request);
        }
    }

    public static function getAuthorMyProfile(Request $request) :string {
        try {
            $userSessionData = LoginSession::getUserSession();
            $authorEntity = AuthorEntity::getAuthorById($userSessionData['id']);

            return self::renderMyProfile($request, [
                'name' => $authorEntity->name,
                'description' => $authorEntity->description,
            ]);
        } catch (Exception $exception){
            return self::renderMyProfile($request, [
                'name' => $userSessionData['name'],
                'description' => null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    public static function getUserMyProfile(Request $request) :string {
        try {
            $userSessionData = LoginSession::getUserSession();
            $authorEntity = UserEntity::getUserById($userSessionData['id']);

            return self::renderMyProfile($request, [
                'name' => $authorEntity->name,
            ]);
        } catch (Exception $exception){
            return self::renderMyProfile($request, [
                'name' => $userSessionData['name'],
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    public static function updateAuthor(Request $request) :string {
        $postVars = $request->getPostVars();
        $loginSessionData = LoginSession::getUserSession();
        try {
            $authorEntity = AuthorEntity::getAuthorById($loginSessionData['id']);
            $authorEntity->setName($postVars['name']);
            $authorEntity->setDescription($postVars['description']);
            $authorEntity->update();

            LoginSession::setUserSession($authorEntity);

            return self::renderMyProfile($request, [
                'name' => $authorEntity->name,
                'description' => $authorEntity->description,
            ]);
        } catch (Exception $exception) {
            return self::renderMyProfile($request, [
                'name' => $postVars['name'] ?? null,
                'description' => $postVars['description'] ?? null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    private static function createAuthor(Request $request) : string {
        $postVars = $request->getPostVars();
        $loginSessionData = LoginSession::getUserSession();
        try {
            $userEntity = UserEntity::getUserById($loginSessionData['id']);
            $authorEntity = $userEntity->createAuthor($postVars['name'], $postVars['description']);
            LoginSession::setUserSession($userEntity);

            return self::renderMyProfile($request, [
                'name' => $authorEntity->name,
                'description' => $authorEntity->description,
            ]);
        } catch (Exception $exception) {
            return self::renderMyProfile($request, [
                'name' => $postVars['name'] ?? null,
                'description' => $postVars['description'] ?? null,
                'message' => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ])
            ]);
        }
    }

    private static function renderMyProfile(Request $request, array $params = []) : string
    {
        return Page::getPage($request, [
            'css' => ['/Resources/css/myProfile.css'],
            'content' => View::render("/Panel/myProfile", [
                'name' => $params['name'] ?? null,
                'description' => $params['description'] ?? null,
                'message' => $params['message'] ?? null
            ])
        ]);
    }
}