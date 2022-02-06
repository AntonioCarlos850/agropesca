<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Utils\View;

define('URL', 'https://agroblog');

View::init([
    'URL' => URL,
    'nome' => 'Rafael' 
]);

$router = new \App\Http\Router(URL);

include __DIR__ . '/Routes/Pages.php';
include __DIR__ . '/Routes/Post.php';
include __DIR__ . '/Routes/User.php';

$router->run()
    ->send();