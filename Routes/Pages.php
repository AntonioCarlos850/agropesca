<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/", [
    function() {
        return new Response(Pages\Home::getHome());
    }
]);

$router->get("/teste/{slug}/{id}/{categoria}", [
    function($slug, $id, $categoria) {
        return new Response("Slug: $slug, ID: $id, Categoria: $categoria");
    }
]);