<?php
namespace App\Model\Entity;

use Exception;
use App\Model\Repository\UserRepository;
use App\Utils\Helpers;
use User;

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
        if(!isset($userData["name"]) || !isset($userData["password"]) || !isset($userData["email"])){
            throw new Exception("Nome, email e senha necessários", 400);
        }

        $this->id = $userData["id"] ?: null;
        $this->name = $userData["name"];

        if(!isset($userData["password_salt"])){
            $this->setPassword($userData["password"]);
        }else{
            $this->password = $userData["password"];
            $this->password_salt = $userData["password_salt"];
        }
        
        $this->creation_date = $userData["creation_date"] ?: null;
        $this->update_date = $userData["update_date"] ?: null;

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

    public static function tryLogin(string $email, string $password){
        if(!$email || !$password){
            throw new Exception("Email e senha necessários", 400);
        }

        $userData = UserRepository::getUserByEmail($email);

        if(!$userData){
            throw new Exception("Email não encontrado");
        }

        if(sha1($password.$userData["password_salt"]) == $userData["password"]){
            $userEntity = new UserEntity($userData);

            return $userEntity;
        }else{
            throw new Exception("Senha incorreta", 403);
        }
    }

    public static function createUser(string $email, string $name, string $password)
    {
        if(!$email || !$name || !$password){
            throw new Exception("Nome, email ou senha não fornecidos", 400);
        }

        $userData = UserRepository::getUserByEmail($email);

        if($userData){
            throw new Exception("Email já cadastrado", 403);
        }

        $userEntity = new UserEntity([
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);

        $userEntity->create();

        return $userEntity;
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

    public function create(){
        $userId = UserRepository::createUser([
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);

        $this->id = $userId;
    }

    public function update(){
        UserRepository::updateUserById($this->id, [
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);
    }

    public function delete(){
        UserRepository::deleteUserById($this->id);
    }
}