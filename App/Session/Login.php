<?php

namespace App\Session;

use App\Model\Entity\UserEntity;

class Login {
    private static function init():void{
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    public static function setUserSession(UserEntity $objectUser): void{
        self::init();

        $_SESSION["user"] = [
            'id' => $objectUser->id,
            'name' => $objectUser->name,
            'email' => $objectUser->email
        ];
    }

    public static function getUserSession():array{
        self::init();
        return $_SESSION["user"];
    }

    public static function isLogged():bool{
        self::init();
        return isset($_SESSION["user"]["id"]);
    }

    public static function logout():bool{
        if(self::isLogged()){
            unset($_SESSION["user"]);
            return true;
        }

        return false;
    }
}