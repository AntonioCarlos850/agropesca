<?php

namespace App\Controllers\Pages;

use \App\Utils\View;

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
}