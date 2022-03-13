<?php

use \App\Http\Response;
use \App\Controllers\Pages;
use App\Http\Request;

$router->get("/login", function(Request $request) {
    return new Response(Pages\LoginSession::getLogin());
}, ["requireLogout"]);

$router->post("/login", function(Request $request) {
    return new Response(Pages\LoginSession::tryLogin($request));
}, ["requireLogout"]);

$router->get("/logout", function(Request $request) {
    return new Response(Pages\LoginSession::setLogout($request));
}, ["requireLogin"]);

$router->get("/cadastro", function() {
    return new Response(Pages\Cadastro::getCadastroOrEditarCadastro());
});

$router->post("/cadastro", function(Request $request) {
    return new Response(Pages\Cadastro::cadastroPost($request));
});