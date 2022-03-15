<?php

namespace App\Session;

use App\Model\Entity\UserEntity;
use App\Utils\Helpers;
use Exception;

class LoginSession extends Session{
    public static function setUserSession(UserEntity $objectUser): void{
        self::init();

        $_SESSION['user'] = [
            'id' => $objectUser->id,
            'name' => $objectUser->name,
            'email' => $objectUser->email,
            'type_id' => $objectUser->type->id,
            'image_uri' => $objectUser->getImageUri(),
        ];
    }

    private static function refreshUserData() :void{
        self::init();

        if(self::isLogged()){
            try {
                if(!Helpers::verifyArrayFields($_SESSION['user'], [
                    'id', 'name', 'email', 'type_id', 'image_uri'
                ])){
                    self::setUserSession(UserEntity::getUserById($_SESSION['user']['id']));
                }
            } catch (Exception $exception) {
                unset($_SESSION['user']);
            }
            
        }
    } 

    public static function getUserSession():array{
        self::init();
        self::refreshUserData();
        return $_SESSION['user'] ?? null;
    }

    public static function isLogged():bool{
        self::init();
        return isset($_SESSION['user']['id']);
    }

    public static function logout():bool{
        if(self::isLogged()){
            unset($_SESSION['user']);
            return true;
        }

        return false;
    }
}