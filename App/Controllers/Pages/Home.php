<?php

namespace App\Controllers\Pages;

use \App\Utils\View;
use \App\Model\Entity\User;

class Home extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getHome() :string {
        $usuario = new User();
        
        return Page::getPage([
            "title" => "Home",
            "content" => View::render("pages/home", [
                "title" => "Olá, ".$usuario->name
            ])
        ]);
    }
}