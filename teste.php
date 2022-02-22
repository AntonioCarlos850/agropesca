<?php
include __DIR__ . '/config.php';
use \App\Model\Entity\UserEntity;
use App\Utils\SqlConnection;

try {
    var_dump(json_encode(UserEntity::tryLogin("techiorafael@gmail.com", "rafarafa")));
} catch (Exception $exception){
    var_dump($exception->getMessage());
}