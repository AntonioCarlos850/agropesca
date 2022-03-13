<?php

use \App\Http\Response;
use \App\Controllers\Pages;
use App\Http\Request;

$router->get("/", function() {
    return new Response(Pages\Home::getHome());
});

$router->get("/busca", function(Request $request) {
    return new Response(Pages\Busca::getBusca($request));
});

$router->post("/busca", function(Request $request) {
    return new Response(Pages\Busca::getBusca($request));
});