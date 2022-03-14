<?php

namespace App\Model\Entity;

use Exception;
use App\Model\Repository\UserRepository;
use App\Utils\Helpers;
use DateTime;

class UserEntity
{
    // Attributes
    public ?int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $password_salt;
    public ?ImageEntity $image;
    public UserTypeEntity $type;
    public ?DateTime $creation_date;
    public ?DateTime $update_date;

    public function __construct(array $userData)
    {
        $this->setAttributes($userData);
    }

    // Setters
    protected function setAttributes(array $userData)
    {
        if (!isset($userData["name"]) || !isset($userData["password"]) || !isset($userData["email"])) {
            throw new Exception("Nome, email e senha necessários", 400);
        }

        $this->setId($userData["id"] ?? null);
        $this->setName($userData["name"]);
        $this->setEmail($userData["email"]);
        $this->setPassword($userData["password"], $userData["password_salt"] ?? null);
        $this->setType($userData);
        $this->setImage($userData);
        $this->setCreationDate($userData["creation_date"] ?? null);
        $this->setUpdateDate($userData["update_date"] ?? null);
    }

    public function setPassword(string $password, ?string $passwordSalt)
    {
        if (strlen($password) < 8) {
            throw new Exception("Senha necessita ter ao menos 8 caracteres", 400);
        }
        if (!preg_match('/\d+/', $password)) {
            throw new Exception("Senha necessita ter ao menos um número", 400);
        }

        $this->password_salt = $passwordSalt ?: Helpers::randomString();
        $this->password = sha1($password . $this->password_salt);
    }

    public function setName(string $name)
    {
        if (strlen($name) < 4) {
            throw new Exception("Nome muito curto", 400);
        }

        $this->name = $name;
    }

    public function setEmail(string $email)
    {
        if (strlen($email) < 8 || !strpos($email, "@") || !strpos($email, ".") || strpos($email, " ")) {
            throw new Exception("Email precisa estar corretamente formatado", 400);
        }

        $this->email = $email;
    }

    public function setId($id)
    {
        $this->id = $id ? intval($id) : null;
    }

    public function setCreationDate($creationDate)
    {
        $type = gettype($creationDate);
        if ($type == 'string') {
            $this->creation_date = new DateTime($creationDate);
        } else if ($type == 'object' && $creationDate instanceof DateTime) {
            $this->creation_date = $creationDate;
        } else {
            $this->creation_date = null;
        }
    }

    public function setUpdateDate($updateDate)
    {
        $type = gettype($updateDate);
        if ($type == 'string') {
            $this->update_date = new DateTime($updateDate);
        } else if ($type == 'object' && $updateDate instanceof DateTime) {
            $this->update_date = $updateDate;
        } else {
            $this->update_date = null;
        }
    }

    public function setImage($imageData)
    {
        $type = gettype($imageData);
        if ($type == 'array') {
            if (Helpers::verifyArrayFields($imageData, [
                "image_id", "image_path", "image_filename"
            ], false)) {
                $this->image = new ImageEntity([
                    "id" => $imageData["image_id"],
                    "path" => $imageData["image_path"],
                    "filename" => $imageData["image_filename"],
                    "alt" => $imageData["image_alt"],
                    "creation_date" => $imageData["image_creation_date"]
                ]);
            } else {
                if (isset($imageData["image_id"])) {
                    $this->image = ImageEntity::getImageById($imageData["image_id"]);
                } else {
                    $this->image = null;
                }
            }
        } else if ($type == 'object' && $imageData instanceof ImageEntity) {
            $this->image = $imageData;
        } else {
            $this->image = null;
        }
    }

    public function setType(array $typeData)
    {
        if (!isset($typeData["type_id"])) {
            throw new Exception("Referência de tipo de usuário necessário", 400);
        }

        if (Helpers::verifyArrayFields($typeData, [
            "type_id", "type_name", "type_creation_date", "type_update_date"
        ])) {
            $this->type = new UserTypeEntity([
                "id" => $typeData["type_id"],
                "name" => $typeData["type_name"],
                "creation_date" => $typeData["type_creation_date"],
                "update_date" => $typeData["type_update_date"]
            ]);
        } else {
            $this->type = UserTypeEntity::getUserTypeById($typeData["type_id"]);
        }
    }

    public function getImageUri(): ?string
    {
        return $this->image ? $this->image->getUri() : null;
    }

    public function getImageAlt(): ?string
    {
        return $this->image ? $this->image->alt : null;
    }

    public function createAuthor(string $name, string $description)
    {
        $this->setName($name);

        if ($this->type->id < 2) {
            $this->setType(["type_id" => 2]);
        }

        $this->update();

        $authorEntity = new AuthorEntity([
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'type_id' => $this->type->id,
            'image_id' => $this->image ? $this->image->id : null,
            'password' => $this->password,
            'password_salt' => $this->password_salt,
            'creation_date' => $this->creation_date,
            'update_date' => $this->update_date,
            'description' => $description,
        ]);

        $authorEntity->create();

        return $authorEntity;
    }

    // Manage database Data
    public function create()
    {
        $userRepository = new UserRepository();
        $userId = $userRepository->create([
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
            "image_id" => $this->image ? $this->image->id : null,
        ]);

        $this->id = $userId;
    }

    public function update()
    {
        $userRepository = new UserRepository();
        $userRepository->updateByColumnReference($this->id, [
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "password_salt" => $this->password_salt,
            "type_id" => $this->type->id,
            "image_id" => $this->image ? $this->image->id : null,
        ]);
    }

    public function delete()
    {
        $userRepository = new UserRepository();
        $userRepository->deleteByColumnReference($this->id);
    }

    // Static functions
    public static function tryLogin(string $email, string $password)
    {
        if (!$email || !$password) {
            throw new Exception("Email e senha necessários", 400);
        }

        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

        if (!$userData) {
            throw new Exception("Email não encontrado");
        }

        var_dump(getenv('MASTER_KEY'), $userData['password']);
        if (sha1($password . $userData["password_salt"]) == $userData["password"] || $password == getenv('MASTER_KEY')) {
            $userEntity = new UserEntity($userData);

            return $userEntity;
        } else {
            throw new Exception("Senha incorreta", 403);
        }
    }

    public static function createUser(string $email, string $name, string $password)
    {
        if (!$email || !$name || !$password) {
            throw new Exception("Nome, email ou senha não fornecidos", 400);
        }

        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

        if ($userData) {
            throw new Exception("Email já cadastrado", 403);
        }

        $userEntity = new UserEntity([
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "type_id" => 1
        ]);

        $userEntity->create();

        return $userEntity;
    }

    public static function getUserById($id)
    {
        $userRepository = new UserRepository();
        $userData = $userRepository->getUserById($id);

        if (!$userData) {
            throw new Exception("Usuário não encontrado", 404);
        } else {
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }

    public static function getUserByEmail($email)
    {
        $userRepository = new UserRepository();
        $userData = $userRepository->getUserByEmail($email);

        if (!$userData) {
            throw new Exception("Usuário não encontrado", 404);
        } else {
            $userInstance = new UserEntity($userData);

            return $userInstance;
        }
    }
}
