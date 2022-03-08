<?php

namespace App\Controllers\Pages;

use App\Session\Login;
use \App\Utils\View;

class Home extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getHome() :string {
        $userSession = Login::getUserSession();

        return Page::getPage([
            "title" => "Home",
            "content" => View::render("pages/home", [
                "title" => "Olá, ".$userSession["name"]
            ])
        ]);
    }
}