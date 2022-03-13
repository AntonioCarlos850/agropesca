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

$router->get("/painel/post/{id}/delete", function(Request $request, $id) {
    return new Response(Panel\Post::deletePost($request, $id));
}, ["requireLogin"]);

$router->get("/painel/post/{id}", function(Request $request, $id) {
    return new Response(Panel\Post::getPost($request, intval($id)));
}, ["requireLogin"]);

$router->post("/painel/post/{id}", function(Request $request, $id) {
    return new Response(Panel\Post::editPost($request, $id));
}, ["requireLogin"]);


$router->get("/painel/post", function(Request $request) {
    return new Response(Panel\Post::getNewPost($request));
}, ["requireLogin"]);

$router->post("/painel/post", function(Request $request) {
    return new Response(Panel\Post::createPost($request));
}, ["requireLogin"]);