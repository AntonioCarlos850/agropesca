<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__);

View::init([
    'URL' => getenv('URL'),
    'nome' => 'Rafael' 
]);

$router = new \App\Http\Router(getenv('URL'));

include __DIR__ . '/Routes/Pages.php';
include __DIR__ . '/Routes/Post.php';
include __DIR__ . '/Routes/User.php';

$router->run()
    ->send();