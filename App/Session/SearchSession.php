<?php

namespace App\Session;

use App\Model\Entity\UserEntity;
use App\Utils\Helpers;

class SearchSession extends Session{
    public static function setSearchSession(array $param = []): void{
        self::init();
        $_SESSION["search"] = [
            'conditions' => [
                'search' => $param['search'] ?? ($_SESSION['search']['conditions']['search'] ?? null),
                'author' => $param['author'] ?? ($_SESSION['search']['conditions']['author'] ?? null),
                'category' => $param['category'] ?? ($_SESSION['search']['conditions']['category'] ?? null),
            ],
            'orders' => $param['orders'] ?? ($_SESSION['search']['orders'] ?: [
                'relevancia'
            ]),
            'page' => $param['page'] ?? ($_SESSION['search']['page'] ?? 1),
            'itensPerPage' => 3
        ];
    }

    public static function getSearchSession():array{
        self::init();
        return $_SESSION["search"];
    }
}