<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Model\Entity\UserEntity;
use App\Session\Login;
use Exception;

class Cadastro extends Page
{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getCadastro(array $params = []): string
    {
        return Page::getPage([
            'title' => 'Cadastro',
            'css' => View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => "/Resources/css/cadastro.css"
            ]),
            'content' => View::render('Pages/cadastro', [
                "emailInputValue" => $params["emailInputValue"] ?? null,
                "nameInputValue" => $params["nameInputValue"] ?? null,
                "emailInputMessage" => $params["emailInputMessage"] ?? null,
                "message" => $params["message"] ?? null,
            ])
        ]);
    }

    public static function getEditarCadastro(array $params = []): string
    {
        $userSessionData = Login::getUserSession();

        return Page::getPage([
            'title' => 'Editar Informações de Cadastro',
            'css' => View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => "/Resources/css/cadastro.css"
            ]),
            'content' => View::render('Pages/editarCadastro', [
                "nameInputValue" => $params["nameInputValue"] ?? $userSessionData["name"],
                "message" => $params["message"] ?? null,
                "firstName" => $params["firstName"] ?? explode(" ", $userSessionData["name"])[0]
            ])
        ]);
    }

    public static function getCadastroOrEditarCadastro(): string
    {
        if (Login::isLogged()) {
            return self::getEditarCadastro();
        } else {
            return self::getCadastro();
        }
    }

    public static function cadastroPost($request): string
    {
        if (Login::isLogged()) {
            return self::editarCadastro($request);
        } else {
            return self::cadastrar($request);
        }
    }

    public static function editarCadastro($request): string
    {
        $postVars = $request->getPostVars();
        $userSessionData = Login::getUserSession();
        $cadastroParams = [];

        try {
            $userEntity = UserEntity::getUserById($userSessionData["id"]);
            $userEntity->setName($postVars["name"]);

            $userEntity->update();


            Login::setUserSession($userEntity);

            $cadastroParams = [
                "nameInputValue" => $userSessionData["name"],
                "message" => View::render('Components/Page/divMessage', [
                    "message" => "Informações editas com sucesso",
                    "divClass" => "success-message"
                ]),
                "firstName" => explode(" ", $userEntity->name)[0]
            ];
        } catch (Exception $exception) {
            $cadastroParams =  [
                "nameInputValue" => $userSessionData["name"],
                "message" => View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ]),
                "firstName" => explode(" ", $userSessionData["name"])[0]
            ];
        }

        return self::getEditarCadastro($cadastroParams);
    }

    public static function cadastrar($request): string
    {
        $postVars = $request->getPostVars();

        $cadastroParams = [
            "emailInputValue" => $postVars["email"],
            "nameInputValue" => $postVars["name"],
            "message" => null
        ];

        if ($postVars["password"] != $postVars["confirmation-password"]) {
            $cadastroParams["message"] = View::render('Components/Page/divMessage', [
                "message" => "As senhas precisam ser iguais",
                "divClass" => "error-message"
            ]);
        } else {
            try {
                $userEntity = UserEntity::createUser($postVars["email"], $postVars["name"], $postVars["password"]);
                $cadastroParams["emailInputValue"] = $userEntity->email;
                $cadastroParams["nameInputValue"] = $userEntity->name;
            } catch (Exception $exception) {
                $cadastroParams["message"] = View::render('Components/Page/divMessage', [
                    "message" => $exception->getMessage(),
                    "divClass" => "error-message"
                ]);
            }
        }

        return self::getCadastro($cadastroParams);
    }
}
