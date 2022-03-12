<?php

namespace App\Controllers\Panel;

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

            "css" => $params["css"] ?? '',
            "headScripts" => $params["headScripts"] ?? '',
            "endBodyScripts" => $params["endBodyScripts"] ?? '',
        ]);
    }

    public static function renderCss(array $links){
        $renderedCss = [];

        foreach($links as $link){
            $renderedCss = View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }

        return join(" ", $renderedCss);
    }

    public static function renderJs(array $links){
        $renderedCss = [];

        foreach($links as $link){
            $renderedCss = View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }

        return join(" ", $renderedCss);
    }
}