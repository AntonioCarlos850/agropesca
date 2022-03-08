<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/login", function() {
    return new Response(Pages\Login::getLogin());
}, ["requireLogout"]);

$router->post("/login", function($request) {
    return new Response(Pages\Login::tryLogin($request));
}, ["requireLogout"]);

$router->get("/logout", function($request) {
    return new Response(Pages\Login::setLogout($request));
});

$router->get("/cadastro", function() {
    return new Response(Pages\Cadastro::getCadastro());
});

$router->post("/cadastro", function($request) {
    return new Response(Pages\Cadastro::cadastrar($request));
});