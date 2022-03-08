<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/", [
    "middlewares" => [
        "requireLogin"
    ],
    function() {
        return new Response(Pages\Home::getHome());
    }
]);

$router->get("/teste/{slug}/{id}/{categoria}", [
    function($slug, $id, $categoria) {
        return new Response("Slug: $slug, ID: $id, Categoria: $categoria");
    }
]);

$router->get("/formulario", [
    function() {
        return new Response(Pages\Formulario::getFormulario());
    }
]);

$router->post("/formulario", [
    function($request) {
        var_dump($request);
        return new Response("Recebi um post de formulário");
    }
]);