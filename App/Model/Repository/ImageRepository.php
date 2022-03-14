<?php

namespace App\Model\Repository;

use App\Model\Repository\Repository;

class ImageRepository extends Repository
{
    public function __construct()
    {
        parent::__construct('blg_image', 'id');
    }

    public function getImage(array $queryConditions = [], array $data = [])
    {
        return self::selectRow(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            WHERE " . join(" AND ", $queryConditions),
            $data
        );
    }

    public function getImages(array $queryConditions = [], array $queryOrders = [], $limit, $offset, array $data = [])
    {
        return self::select(
            "SELECT {$this->tableName}.*
            FROM {$this->tableName}
            " . (empty($queryConditions) ? "" : ("WHERE " . join(" AND ", $queryConditions))) . "
            ORDER BY " . (empty($queryOrders) ? "{$this->tableName}.{$this->columnReference} DESC" : join(", ", $queryOrders) . "
            " . ($limit ? "LIMIT $limit" : "") . "
            " . ($limit && $offset ? "OFFSET $offset" : "") . "
            "),
            $data
        );
    }

    public function getImageById($id)
    {
        return self::getImage(["{$this->tableName}.{$this->columnReference} = :id"], ["id" => $id]);
    }
}
