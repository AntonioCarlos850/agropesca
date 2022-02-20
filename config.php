<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Utils;
use \App\Model\Repository\Repository;
use \WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__);

Utils\View::init([
    'URL' => getenv('URL'),
    'nome' => 'Rafael' 
]);

Utils\SqlConnection::init(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_NAME")
);

Repository::$connection = new Utils\SqlConnection();
