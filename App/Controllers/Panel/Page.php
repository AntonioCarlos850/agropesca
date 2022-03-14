<?php

namespace App\Controllers\Panel;

use App\Http\Request;
use App\Session\LoginSession;
use \App\Utils\View;

class Page
{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPage(Request $request, array $params = []): string
    {
        return View::render("Panel/page", [
            "title" => $params["title"] ?? 'Painel | Blog Agropesca',
            "metaDescription" => $params["metaDescription"] ?? "O maior Blog de Agropesca do Oeste Paranaense",

            "content" => $params["content"] ?? '',
            "header" => self::renderSideBar($request, $params["aditionalSidebarLinks"] ?? []),
            "css" => self::renderCss(array_merge(['/Resources/css/global.css'], $params["css"] ?? [])),
            "headScripts" => self::renderJs($params["headScripts"] ?? []),
            "endBodyScripts" => self::renderJs($params["endBodyScripts"] ?? []),
        ]);
    }

    public static function renderSideBar(Request $request, array $aditionalLinks = [])
    {
        $userLoginSession = LoginSession::getUserSession();
        return View::render('Components/Panel/sidebar', [
            'content' => array_map(function (array $param) use ($request) {
                return View::render('Components/Panel/sidebarItemLink', array_merge($param, [
                    'class' => $request->getUri() == $param['link'] ? 'active' : ''
                ]));
            }, array_merge(
                $userLoginSession['type_id'] > 1 ? [
                    ['icon' => 'fa-solid fa-plus', 'link' => '/painel/post', 'content' => 'Novo Post',],
                    ['icon' => 'fa-solid fa-pen', 'link' => '/painel/myPosts', 'content' => 'Meus Posts',],
                ] : [],
                [
                    ['icon' => 'fa-solid fa-user', 'link' => '/painel/myProfile', 'content' => 'Meu Perfil',],
                    ['icon' => 'fas fa-arrow-alt-circle-left', 'link' => '/', 'content' => 'Voltar ao site',]
                ],
                $aditionalLinks
            ))
        ]);
    }

    public static function renderCss(array $links): ?array
    {
        return array_map(function (string $link) {
            return View::render('Components/Page/link', [
                "rel" => "stylesheet",
                "href" => $link
            ]);
        }, $links);
    }

    public static function renderJs(array $links)
    {
        return array_map(function ($link) {
            if (gettype($link) == 'array') {
                return View::render('Components/Page/script', [
                    "src" => isset($link['src']) ? ('src="' . $link['src'] . '"') : '',
                    "referrerpolicy" => isset($link['referrerpolicy']) ? ('referrerpolicy="' . $link['referrerpolicy'] . '"') : null,
                    "content" => $link['content'] ?? null,
                ]);
            } else if (gettype($link) == 'string') {
                return View::render('Components/Page/script', [
                    "href" => 'src="' . $link . '"',
                    "referrerpolicy" => null,
                    "content" => null,
                ]);
            }
        }, $links);
    }
}
