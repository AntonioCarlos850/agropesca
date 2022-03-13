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
            "header" => self::getHeader($params),
            "footer" => $params["footer"] ?? '',

            "css" => self::renderCss(array_merge(['/Resources/css/global.css'], $params["css"] ?? [])),
            "headScripts" => self::renderJs(array_merge($params["headScripts"] ?? [], (!!$params['navbar'] ?? false) ? ['https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', '/Resources/js/navbar.js'] : [])),
            "endBodyScripts" => self::renderJs($params["endBodyScripts"] ?? []),
        ]);
    }

    private static function getHeader(array $params = []){
        $header = $params['header'] ?? '';
        if(!$header && (!!$params['navbar'] ?? false)){
            $header = self::renderNavbar();
        }

        return $header;
    }

    public static function renderNavbar():string {
        return View::render('Components/Page/navbar', []);
    }

    public static function renderCss(array $links):?array{
        return array_map(function (string $link){
            return View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }

    public static function renderJs(array $links):?array{
        return array_map(function (string $link){
            return View::render('Components/Page/script', [
                "src" => $link
            ]);
        }, $links);
    }
}