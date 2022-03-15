<?php

use \App\Http\Response;
use \App\Controllers\Pages;
use App\Http\Request;
use App\Model\Entity\UserEntity;
use App\Session\LoginSession;

$router->get("/", function () {
    return new Response(Pages\Home::getHome());
});

$router->get("/autor/{slug}", function (Request $request, $slug) {
    return new Response(Pages\Author::getAuthor($request, $slug));
});

$router->get("/post/{slug}", function ($slug, $request) {
    return new Response(Pages\Post::getPost($slug, $request));
});

$router->get("/busca", function (Request $request) {
    return new Response(Pages\Busca::getBusca($request));
});

$router->post("/busca", function (Request $request) {
    return new Response(Pages\Busca::getBusca($request));
});

$router->get("/sobre-nos", function ($request) {
    return new Response(Pages\AboutUs::getAboutUs($request));
});

$router->get("/session", function () {
    $userSessionData = LoginSession::getUserSession();
    LoginSession::setUserSession(UserEntity::getUserById($userSessionData['id']));
    return new Response('');
});