<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Model\Entity\UserEntity;
use Exception;

class Cadastro extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getCadastro() :string {
        return Page::getPage([
            'title' => 'Cadastro',
            'css' => View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => "/Resources/css/cadastro.css"
            ]),
            'content' => View::render('Pages/cadastro', [
                "emailInputValue" => null,
                "nameInputValue" => null,
                "emailInputMessage" => null,
                "message" => null,
            ])
        ]);
    }

    public static function cadastrar($request) :string {
        $postVars = $request->getPostVars();
        if($postVars["password"] != $postVars["confirmation-password"]){
            return Page::getPage([
                'title' => 'Cadastro',
                'css' => View::render('Components/Page/link', [
                    "rel" => "stylesheet",
                    "href" => "/Resources/css/cadastro.css"
                ]),
                'content' => View::render('Pages/cadastro', [
                    "emailInputValue" => $postVars["email"],
                    "nameInputValue" => $postVars["name"],
                    "message" => View::render('Components/Page/divMessage', [
                        "message" => "As senhas precisam ser iguais",
                        "divClass" => "error-message"
                    ]),
                ])
            ]);
        }else{
            try{
                $userEntity = UserEntity::createUser($postVars["email"], $postVars["name"], $postVars["password"]);
                header('Location: home');
            } catch (Exception $exception) {
                return Page::getPage([
                    'title' => 'Cadastro',
                    'css' => View::render('Components/Page/link', [
                        "rel" => "stylesheet",
                        "href" => "/Resources/css/cadastro.css"
                    ]),
                    'content' => View::render('Pages/cadastro', [
                        "emailInputValue" => $postVars["email"],
                        "nameInputValue" => $postVars["name"],
                        "message" => View::render('Components/Page/divMessage', [
                            "message" => $exception->getMessage(),
                            "divClass" => "error-message"
                        ]),
                    ])
                ]);
            }
        }
    }
}