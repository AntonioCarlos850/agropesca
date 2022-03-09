<?php

namespace App\Controllers\Pages;

use App\Session\Login;
use \App\Utils\View;

class Index extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getIndex() :string {
        $userSession = Login::getUserSession();
        $css = Page::renderCss(["/Resources/css/global.css","/Resources/css/index.css","/Resources/css/most_views.css"]);

        return Page::getPage([
            "title" => "Agroblog | Home",
            'css' => $css,
            "content" => View::render("pages/home", [
                "title" => "Olá, ".$userSession["name"]
            ])
        ]);
    }
}