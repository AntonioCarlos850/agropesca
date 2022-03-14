<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class UserTypeRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('blg_user_type', 'id');
    }

    public function getUserType(array $queryConditions = [], array $data = [])
    {
        return self::selectRow(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            WHERE " . join(" AND ", $queryConditions),
            $data
        );
    }

    public function getUserTypes(array $queryConditions = [], array $queryOrders = [], array $queryJoins = [], ?int $limit = null, ?int $offset = null, array $data = [])
    {
        return self::select(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            " . (join(" ", $queryJoins)) . "
            " . (empty($queryConditions) ? "" : ("WHERE " . join(" AND ", $queryConditions))) . "
            ORDER BY " . (empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders) . "
            " . ($limit ? "LIMIT $limit" : "") . "
            " . ($limit && $offset ? "OFFSET $offset" : "") . "
            "),
            $data
        );
    }

    public function getUserTypeById($id)
    {
        return self::getUserType(["{$this->tableName}.{$this->columnReference} = :id"], ["id" => $id]);
    }
}
