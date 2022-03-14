<?php

use \App\Http\Response;
use \App\Controllers\Panel;
use App\Http\Request;

$router->get("/painel/", function (Request $request) {
    $request->getRouter()->redirect("/painel/myPosts");
}, ["requireLogin", "requireAuthorType"]);

$router->post("/painel/myProfile/image", function (Request $request, $id) {
    return new Response(Panel\MyProfile::editImage($request));
}, ["requireLogin"]);

$router->get("/painel/myProfile", function (Request $request) {
    return new Response(Panel\MyProfile::getMyProfile($request));
}, ["requireLogin"]);

$router->post("/painel/myProfile", function (Request $request) {
    return new Response(Panel\MyProfile::postMyProfile($request));
}, ["requireLogin"]);

$router->post("/painel/myPosts", function (Request $request) {
    return new Response(Panel\MyPosts::getMyPosts($request));
}, ["requireLogin", "requireAuthorType"]);

$router->get("/painel/myPosts", function (Request $request) {
    return new Response(Panel\MyPosts::getMyPosts($request));
}, ["requireLogin", "requireAuthorType"]);

$router->post("/painel/post/{id}/image", function (Request $request, $id) {
    return new Response(Panel\Post::editImage($request, $id));
}, ["requireLogin", "requireAuthorType"]);

$router->get("/painel/post/{id}/delete", function (Request $request, $id) {
    return new Response(Panel\Post::deletePost($request, $id));
}, ["requireLogin", "requireAuthorType"]);

$router->get("/painel/post/{id}", function (Request $request, $id) {
    return new Response(Panel\Post::getPost($request, intval($id)));
}, ["requireLogin", "requireAuthorType"]);

$router->post("/painel/post/{id}", function (Request $request, $id) {
    return new Response(Panel\Post::editPost($request, $id));
}, ["requireLogin", "requireAuthorType"]);


$router->get("/painel/post", function (Request $request) {
    return new Response(Panel\Post::getNewPost($request));
}, ["requireLogin", "requireAuthorType"]);

$router->post("/painel/post", function (Request $request) {
    return new Response(Panel\Post::createPost($request));
}, ["requireLogin", "requireAuthorType"]);
