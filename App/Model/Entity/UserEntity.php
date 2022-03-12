<?php
namespace App\Model\Entity;

use Exception;
use App\Model\Repository\UserRepository;
use App\Utils\Helpers;
use DateTime;

class UserEntity{
    // Attributes
    public ?int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $password_salt;
    public ?UserTypeEntity $type;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $userData){
        $this->setAttributes($userData);
    }

    // Setters
    protected function setAttributes(array $userData){
        if(!isset($userData["name"]) || !isset($userData["password"]) || !isset($userData["email"])){
            throw new Exception("Nome, email e senha necessários", 400);
        }

        $this->setId($userData["id"] ?? null);
        $this->setName($userData["name"]);
        $this->setEmail($userData["email"]);
        $this->setPassword($userData["password"], $userData["password_salt"] ?? null);
        $this->setType($userData);
        $this->setCreationDate($userData["creation_date"]);
        $this->setUpdateDate($userData["update_date"]);
    }

    public function setPassword(string $password, ?string $passwordSalt){
        if(strlen($password) < 8){
            throw new Exception("Senha necessita ter ao menos 8 caracteres", 400);
        }
        if(!preg_match('/\d+/', $password)){
            throw new Exception("Senha necessita ter ao menos um número", 400);
        }

        $this->password_salt = $passwordSalt ?: Helpers::randomString();
        $this->password = sha1($password.$this->password_salt);
    }

    public function setName(string $name){
        if(strlen($name) < 4){
            throw new Exception("Nome muito curto", 400);
        }

        $this->name = $name;
    }

    public function setEmail(string $email){
        if(strlen($email) < 8 || !strpos($email, "@") || !strpos($email, ".") || strpos($email, " ")){
            throw new Exception("Email precisa estar corretamente formatado", 400);
        }

        $this->email = $email;
    }

    public function setId($id){
        $this->id = $id ? intval($id) : null;
    }

    public function setCreationDate(?string $creationDate){
        $this->creation_date = $creationDate ? new DateTime($creationDate) : null;
    }

    public function setUpdateDate(?string $updateDate){
        $this->update_date = $updateDate ? new DateTime($updateDate) : null;
    }

    public function setType(array $typeData){
        $this->type = new UserTypeEntity([
            "id" => $typeData["type_id"],
            "name" => $typeData["type_name"],
            "creation_date" => $typeData["type_creation_date"],
            "update_date" => $typeData["type_update_date"]
        ]);
    }

    // Manage database Data
    public function create(){
        $userRepository = new UserRepository();
        $userId = $userRepository->create([
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);
        
        $this->id = $userId;
    }

    public function update(){
        $userRepository = new UserRepository();
        $userRepository->updateByColumnReference($this->id, [
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
        ]);
    }

    public function delete(){
        $userRepository = new UserRepository();
        $userRepository->deleteByColumnReference($this->id);
    }

    // Static functions
    public static function tryLogin(string $email, string $password){
        if(!$email || !$password){
            throw new Exception("Email e senha necessários", 400);
        }

        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

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

        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

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
        $userRepository = new UserRepository();
        $userData = $userRepository->getUserById($id);

        if(!$userData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }

    public static function getUserByEmail($email){
        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

        if(!$userData){
            throw new Exception("Usuário não encontrado", 404);
        }else{
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }
}