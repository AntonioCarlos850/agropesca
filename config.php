<?php
require __DIR__ . '/vendor/autoload.php';

use App\Http\Middleware\Queue;
use \App\Utils;
use \App\Model\Repository\Repository;
use \WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__);

Utils\View::init([
    'URL' => getenv('URL'),
]);

Utils\SqlConnection::init(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_NAME")
);

Queue::setMap([
    "maintence" => \App\Http\Middleware\Maintence::class,
    "requireLogin" => \App\Http\Middleware\RequireLogin::class,
    "requireLogout" => \App\Http\Middleware\RequireLogout::class,
    "requireAuthorType" => \App\Http\Middleware\RequireAuthorType::class,
    "PostAuthorOrAdm" => \App\Http\Middleware\PostAuthorOrAdm::class
]);

Queue::setDefault([
    "maintence"
]);

Repository::$connection = new Utils\SqlConnection();

error_reporting(0);
ini_set('display_errors', 0);
