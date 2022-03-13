<?php

namespace App\Controllers\Pages;

use App\Session\LoginSession;
use \App\Utils\View;

class Busca extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getBusca() :string {
        return Page::getPage([
            "title" => "Agroblog | Home",
            'css' => [],
            'navbar' => true,
            "content" => View::render("pages/busca", [
                "qtd" => null,
                "search" => null,
                "searchPosts" => null,
                "mostViewsPosts" => null,
                "weekPost" => null,
            ])
        ]);
    }
}