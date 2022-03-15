<?php

namespace App\Controllers\Pages;

use App\Http\Request;
use \App\Utils\View;

class AboutUs extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     */
    public static function getAboutUs(Request $request) :string {
        return Page::getPage([
            'title' => "Sobre nós | Agroblog",
            'css' => ['/Resources/css/about_us.css'],
            'content' => View::render('pages/aboutUs')
        ]);
    }
}