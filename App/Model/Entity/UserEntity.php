<?php
namespace App\Model\Entity;

use Exception;
use App\Model\Repository\UserRepository;
use App\Utils\Helpers;

class UserEntity{
    // Attributes
    public ?int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $password_salt;
    public ?string $creation_date;
    public ?string $update_date;
    public ?UserTypeEntity $type;

    public function __construct(array $userData){
        $this->setAttributes($userData);
    }

    // Setters
    protected function setAttributes(array $userData){
        if(!isset($userData["name"]) || !isset($userData["password"]) || !isset($userData["email"])){
            throw new Exception("Nome, email e senha necessários", 400);
        }

        $this->id = $userData["id"] ?? null;
        $this->setName($userData["name"]);
        $this->setEmail($userData["email"]);

        if(!isset($userData["password_salt"])){
            $this->setPassword($userData["password"]);
        }else{
            $this->password = $userData["password"];
            $this->password_salt = $userData["password_salt"];
        }

        if(isset($userData["type_id"])){
            $this->type = new UserTypeEntity([
                "id" => $userData["type_id"],
                "name" => $userData["type_name"],
            ]);
        }
        
        $this->creation_date = $userData["creation_date"] ?? null;
        $this->update_date = $userData["update_date"] ?? null;
    }

    public function setPassword(string $password){
        if(strlen($password) < 8){
            throw new Exception("Senha necessita ter ao menos 8 caracteres", 400);
        }
        if(!preg_match('/\d+/', $password)){
            throw new Exception("Senha necessita ter ao menos um número", 400);
        }

        $this->password_salt = Helpers::randomString();
        $this->password = sha1($password.$this->password_salt);
    }

    public function setName(string $name){
        if(strlen($name) < 4 || !strpos($name, " ")){
            throw new Exception("Nome necessita estar completo", 400);
        }

        $this->name = $name;
    }

    public function setEmail(string $email){
        if(strlen($email) < 8 || !strpos($email, "@") || !strpos($email, ".") || strpos($email, " ")){
            throw new Exception("Email precisa estar corretamente formatado", 400);
        }

        $this->email = $email;
    }

    // Manage database Data
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

    // Static functions
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
}