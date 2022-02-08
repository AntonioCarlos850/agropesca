<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/login", [
    function() {
        return new Response(Pages\Login::getLogin());
    }
]);

$router->post("/login", [
    function($email, $password) {
        
        return new Response(Pages\Login::getLogin());
    }
]);

$router->get("/cadastro", [
    function() {}
]);