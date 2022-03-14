<?php

namespace App\Session;

use App\Model\Entity\UserEntity;
use App\Utils\Helpers;

class SearchSession extends Session{
    public static function setSearchSession(array $param = []): void{
        self::init();
        $_SESSION["search"] = [
            'search' => $param['search'] ?? ($_SESSION['search']['search'] ?? null),
            'order' => $param['order'] ?? 'relevancia',
            'page' => $param['page'] ?? ($_SESSION['search']['page'] ?? 1),
            'itensPerPage' => 3
        ];
    }

    public static function getSearchSession():array{
        self::init();
        return $_SESSION["search"];
    }
}