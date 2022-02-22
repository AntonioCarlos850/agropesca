<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Model\Entity\UserEntity;
use Exception;

class Login extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getLogin() :string {
        return Page::getPage([
            'title' => 'Home',
            'css' => View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => "/Resources/css/login.css"
            ]),
            'content' => View::render('Pages/login', [
                "emailInputValue" => null,
                "emailInputMessage" => null,
                "passwordInputMessage" => null,
            ])
        ]);
    }

    public static function tryLogin($request) :string {
        $postVars = $request->getPostVars();
        try{
            $userEntity = UserEntity::tryLogin($postVars["email"], $postVars["password"]);
            return Page::getPage([
                'title' => 'Home',
                'css' => View::render('Components/Page/link', [
                    "rel" => "stylesheet",
                    "href" => "/Resources/css/login.css"
                ]),
                'content' => "Deu certo! Bem vindo, ". $userEntity->name
            ]);
        } catch (Exception $exception) {
            return Page::getPage([
                'title' => 'Home',
                'css' => View::render('Components/Page/link', [
                    "rel" => "stylesheet",
                    "href" => "/Resources/css/login.css"
                ]),
                'content' => View::render('Pages/login', [
                    "emailInputValue" => $postVars["email"],
                    "emailInputMessage" => $exception->getMessage(),
                    "passwordInputMessage" => null,
                ])
            ]);
        }
    }
}