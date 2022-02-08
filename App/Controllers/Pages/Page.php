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
            "headerScripts" => $params["headerScripts"] ?? '',
            "endBodyScripts" => $params["endBodyScripts"] ?? '',
        ]);
    }
}