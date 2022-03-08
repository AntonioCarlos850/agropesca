<?php

namespace App\Controllers\Pages;

use \App\Http\Request;
use \App\Utils\View;
use \App\Model\Entity\UserEntity;
use \App\Session\Login as SessionLogin;
use Exception;

class Login extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getLogin(array $params = []) :string {
        return Page::getPage([
            'title' => 'Home',
            'css' => View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => "/Resources/css/login.css"
            ]),
            'content' => View::render('Pages/login', [
                "emailInputValue" => $params["emailInputValue"] ?? null,
                "emailInputMessage" => $params["emailInputMessage"] ?? null,
                "passwordInputMessage" => $params["passwordInputMessage"] ?? null,
            ])
        ]);
    }

    public static function tryLogin(Request $request) :string {
        $postVars = $request->getPostVars();
        try{
            $userEntity = UserEntity::tryLogin($postVars["email"], $postVars["password"]);
            SessionLogin::setUserSession($userEntity);
            $request->getRouter()->redirect("/");
        } catch (Exception $exception) {
            return self::getLogin([
                "emailInputValue" => $postVars["email"],
                "emailInputMessage" => $exception->getMessage(),
            ]);
        }
    }

    public static function setLogout(Request $request){
        SessionLogin::logout();
        $request->getRouter()->redirect('/login');
    }
}