<?php
include __DIR__ . '/config.php';

use App\Model\Entity\PostEntity;
use \App\Utils\SqlConnection;
use \App\Model\Entity\UserEntity;

try {
    $post = new PostEntity([
        "title" => "Rafael Techio Teste de POST",
        "body" => "teste@gmail.com",
        "description" => "descricao",
        "author_id" => 70
    ]);
    $post->setCategory(1);
    $post->create();

    // $user->create();

    // var_dump(
        // SqlConnection::insert("INSERT INTO blg_user (name, email) VALUES (:name, :email)", ["email" => "techiorafael@gmail.com", "name" => "Rafael"]);
        
    //     // SqlConnection::select("SELECT * FROM bgluser WHERE bgl_user.name ='1'", ["name" => "1"])
    // );
} catch (Exception $exception){
    var_dump($exception->getMessage());
}