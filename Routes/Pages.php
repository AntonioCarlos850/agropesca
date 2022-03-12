<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/", function() {
    return new Response(Pages\Home::getHome());
},["requireLogin"]);

$router->get("/busca", function() {
    return new Response(Pages\Busca::getBusca());
},["requireLogin"]);