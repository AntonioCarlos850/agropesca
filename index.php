<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Controllers\Pages\Home;
// echo Home::getHome();

define("URL", "https://agropesca");

$reponse = new \App\Http\Router(URL);

$reponse->get("/", [
    function() {
        return Home::getHome();
    }
]);

$reponse->run();
exit;