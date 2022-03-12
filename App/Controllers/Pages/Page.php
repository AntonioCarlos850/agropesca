<?php

namespace App\Controllers\Pages;

use \App\Utils\View;

class Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPage(array $params = []) :string {
        return View::render("pages/page", [
            "title" => $params["title"] ?? 'Blog Agropesca',
            "metaDescription" => $params["metaDescription"] ?? "O maior Blog de Agropesca do Oeste Paranaense",

            "content" => $params["content"] ?? '',
            "header" => $params["header"] ?? '',
            "footer" => $params["footer"] ?? '',

            "css" => $params["css"] ?? '',
            "headScripts" => $params["headScripts"] ?? '',
            "endBodyScripts" => $params["endBodyScripts"] ?? '',
        ]);
    }

    public static function renderNavbar():string {
        return View::render('Components/Page/navbar', []);
    }

    public static function renderCss(array $links):array{
        return array_map(function (string $link){
            return View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }

    public static function renderJs(array $links):array{
        return array_map(function (string $link){
            return View::render('Components/Page/script', [
                "src" => $link
            ]);
        }, $links);
    }
}