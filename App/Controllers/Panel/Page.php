<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use \App\Utils\View;

class Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPage(Request $request, array $params = []) :string {
        return View::render("Panel/page", [
            "title" => $params["title"] ?? 'Painel | Blog Agropesca',
            "metaDescription" => $params["metaDescription"] ?? "O maior Blog de Agropesca do Oeste Paranaense",

            "content" => $params["content"] ?? '',
            "header" => self::renderSideBar($request, $params["aditionalSidebarLinks"] ?? []),
            "css" => self::renderCss($params["css"] ?? []),
            "headScripts" => $params["headScripts"] ?? '',
            "endBodyScripts" => $params["endBodyScripts"] ?? '',
        ]);
    }
    
    public static function renderSideBar(Request $request, array $aditionalLinks = []) {
        return View::render('Components/Panel/sidebar', [
            'content' => array_map(function(array $param) use ($request) {
                return View::render('Components/Panel/sidebarItemLink', array_merge($param, [
                    'class' => $request->getUri() == $param['link'] ? 'active' : ''
                ]));
            }, array_merge([
                ['link' => '/painel/myPosts','content' => 'Meus Posts',],
                ['link' => '/painel/myProfile','content' => 'Meu Perfil',],
                ['link' => '/','content' => 'Voltar ao site',]
            ], $aditionalLinks))
        ]);
    }

    public static function renderCss(array $links){
        return array_map(function(string $link){
            View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }

    public static function renderJs(array $links){
        return array_map(function (string $link){
            return View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }
}