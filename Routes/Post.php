<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/post/{slug}", function($slug, $request) {
    return new Response(Pages\Post::getPost($slug, $request));
});