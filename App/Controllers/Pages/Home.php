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
        $css = Page::renderCss(["/Resources/css/global.css","/Resources/css/home.css","/Resources/css/most_views.css"]);

        return Page::getPage([
            "title" => "Agroblog | Home",
            'css' => $css,
            "content" => View::render("pages/home", [
                "title" => "Olá, ".$userSession["name"]
            ])
        ]);
    }

    private static function renderArticles(array $postEntityes)
    {
        
    }
}