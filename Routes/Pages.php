<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/", function() {
    return new Response(Pages\Home::getHome());
});

$router->get("/busca", function() {
    return new Response(Pages\Busca::getBusca());
});

$router->post("/busca", function() {
    return new Response(Pages\Busca::getBusca());
});