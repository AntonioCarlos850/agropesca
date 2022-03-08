<?php

use \App\Http\Response;
use \App\Controllers\Pages;

$router->get("/busca", function() {});

$router->get("/artigo/{slug}", function($slug) {});

$router->get("/painel/artigo/{id}", function($id) {});