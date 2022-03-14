<?php
include __DIR__ . '/config.php';

$router = new \App\Http\Router(getenv('URL'));

include __DIR__ . '/Routes/Panel.php';
include __DIR__ . '/Routes/Pages.php';
include __DIR__ . '/Routes/Post.php';
include __DIR__ . '/Routes/User.php';

$router->run()
    ->send();
