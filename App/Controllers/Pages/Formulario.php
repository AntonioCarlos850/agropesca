<?php

namespace App\Controllers\Pages;

use \App\Utils\View;

class Formulario extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getFormulario() :string {
        return Page::getPage([
            "title" => "Home",
            "content" => View::render("pages/formulario")
        ]);
    }
}