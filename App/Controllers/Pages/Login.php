<?php

namespace App\Controllers\Pages;

use \App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\UserEntity;
use App\Session\LoginSession;
use Exception;

class Login extends Page
{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getLogin(array $params = []): string
    {
        return Page::getPage([
            'title' => 'Home',
            'css' => ['/Resources/css/login.css'],
            'navbar' => true,
            'content' => View::render('Pages/login', [
                "emailInputValue" => $params["emailInputValue"] ?? null,
                "emailInputMessage" => $params["emailInputMessage"] ?? null,
                "passwordInputMessage" => $params["passwordInputMessage"] ?? null,
            ])
        ]);
    }

    public static function tryLogin(Request $request): string
    {
        $postVars = $request->getPostVars();
        try {
            $userEntity = UserEntity::tryLogin($postVars["email"], $postVars["password"]);
            LoginSession::setUserSession($userEntity);
            $request->getRouter()->redirect("/");
        } catch (Exception $exception) {
            return self::getLogin([
                "emailInputValue" => $postVars["email"],
                "emailInputMessage" => $exception->getMessage(),
            ]);
        }
    }

    public static function setLogout(Request $request)
    {
        LoginSession::logout();
        $request->getRouter()->redirect('/login');
    }
}
