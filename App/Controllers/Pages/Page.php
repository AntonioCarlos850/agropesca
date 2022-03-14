<?php

namespace App\Controllers\Pages;

use App\Session\LoginSession;
use \App\Utils\View;

class Page
{
    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     */
    public static function getPage(array $params = []): string
    {
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

    private static function getHeader(array $params = [])
    {
        $header = $params['header'] ?? '';
        if (!$header && (!!$params['navbar'] ?? false)) {
            $header = self::renderNavbar();
        }

        return $header;
    }

    public static function renderNavbar(): string
    {
        $navbarItemLinks = [];
        if (LoginSession::isLogged()) {
            $userSessionData = LoginSession::getUserSession();

            array_push($navbarItemLinks, [
                'link' => '/cadastro',
                'icon' => 'fa-solid fa-user',
                'text' => 'Conta',
            ]);
            if ($userSessionData['type_id'] > 1) {
                array_push($navbarItemLinks, [
                    'link' => '/painel/',
                    'icon' => 'fa-solid fa-pen',
                    'text' => 'Painel de Autor',
                ]);
            } else {
                array_push($navbarItemLinks, [
                    'link' => '/painel/',
                    'icon' => 'fa-solid fa-pen',
                    'text' => 'Tornar-se um Autor',
                ]);
            }
            array_push($navbarItemLinks, [
                'link' => '/logout',
                'icon' => 'fa-solid fa-right-from-bracket',
                'text' => 'Sair',
            ]);
        } else {
            array_push($navbarItemLinks, [
                'link' => '/login',
                'icon' => 'fa-solid fa-user',
                'text' => 'Entrar',
            ]);
        }

        return View::render('Components/Page/navbar', [
            'itemLinks' => array_map(function ($itemLink) {
                return View::render('Components/Page/navbarItemLink', $itemLink);
            }, $navbarItemLinks)
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

    public static function renderJs(array $links): ?array
    {
        return array_map(function (string $link) {
            return View::render('Components/Page/script', [
                "src" => 'src="' . $link . '"',
                "referrerpolicy" => null,
                "content" => $link['content'] ?? null,
            ]);
        }, $links);
    }
}
