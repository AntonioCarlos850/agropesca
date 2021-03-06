<?php

use \App\Http\Response;
use \App\Controllers\Pages;
use App\Http\Request;

$router->get("/login", function (Request $request) {
    return new Response(Pages\Login::getLogin());
}, ["requireLogout"]);

$router->post("/login", function (Request $request) {
    return new Response(Pages\Login::tryLogin($request));
}, ["requireLogout"]);

$router->get("/logout", function (Request $request) {
    return new Response(Pages\Login::setLogout($request));
}, ["requireLogin"]);

$router->get("/cadastro", function (Request $request) {
    return new Response(Pages\Cadastro::getCadastroOrEditarCadastro($request));
});

$router->post("/cadastro", function (Request $request) {
    return new Response(Pages\Cadastro::cadastroPost($request));
});

$router->post("/cadastro/image", function (Request $request) {
    return new Response(Pages\Cadastro::editImage($request));
});
