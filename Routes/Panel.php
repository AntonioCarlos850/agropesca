<?php

use \App\Http\Response;
use \App\Controllers\Panel;
use App\Http\Request;

$router->get("/painel/", function(Request $request) {
    $request->getRouter()->redirect("/painel/myPosts");
}, ["requireLogin"]);

$router->get("/painel/myPosts", function(Request $request) {
    return new Response(Panel\MyPosts::getMyPosts($request));
}, ["requireLogin"]);

$router->get("/painel/post/{id}", function(Request $request, $id) {
    return new Response(Panel\Post::getPost($request, intval($id)));
}, ["requireLogin"]);