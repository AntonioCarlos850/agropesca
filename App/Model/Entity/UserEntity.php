<?php
namespace App\Model\Entity;

use Exception;
use App\Model\Repository\UserRepository;
use App\Utils\Helpers;

class UserEntity{
    public $id;
    public $type;
    public $author;
    public $name;
    public $email;
    public $password;
    public $password_salt;
    public $creation_date;
    public $update_date;

    public function __construct(array $userData){
        $this->setAttributes($userData);
    }

    private function setAttributes(array $userData){
        $this->id = $userData["id"];
        $this->name = $userData["name"];
        $this->password = $userData["password"];
        $this->password_salt = $userData["password_salt"];
        $this->creation_date = $userData["creation_date"];
        $this->update_date = $userData["update_date"];

        $this->type = $userData["type_id"] ? new UserTypeEntity([
            "id" => $userData["type_id"] ?: null,
            "name" => $userData["type_name"] ?: null,
            "create_date" => $userData["type_create_date"] ?: null,
            "update_date" => $userData["type_update_date"] ?: null,
        ]) : null;

        $this->author = $userData["author_create_date"] ? new UserAuthorEntity([
            "userId" => $userData["id"] ?: null,
            "name" => $userData["author_name"] ?: null,
            "slug" => $userData["author_slug"] ?: null,
            "description" => $userData["author_description"] ?: null,
            "create_date" => $userData["author_create_date"] ?: null,
            "update_date" => $userData["author_update_date"] ?: null,
        ]) : null;
    }

    public function setPassword(string $password){
        $this->password_salt = Helpers::randomString();
        $this->password = sha1($password.$this->password_salt);
    }

    public function try_login(string $email, string $password){
        if(!$email || !$password){
            throw new Exception("Email e senha necessários", 400);
        }

        $userData = UserRepository::getUserByEmail($email);        

        if(!$userData){
            throw new Exception("Email não encontrado");
        }

        if(sha1($password.$userData["password_salt"]) == $userData["password"]){
            $this->setAttributes($userData);

            return true;
        }else{
            throw new Exception("Senha incorreta", 403);
        }
    }

    public static function getUserById($id){
        $userData = UserRepository::getUserById($id);

        if(!$userData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }

    public static function getUserByEmail($email){
        $userData = UserRepository::getUserByEmail($email);

        if(!$userData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }

    public function updateUser(){
        UserRepository::updateUserById($this->id, [
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);
    }

    public function deleteUser(){
        UserRepository::deleteUserById($this->id);
    }
}