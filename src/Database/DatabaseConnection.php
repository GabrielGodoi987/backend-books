<?php

namespace Backend\Products\Database;

use Backend\Products\Database\Config\ConfigurationsType;
use PDO;
use PDOException;

class DatabaseConnection
{
    private $pdo;

    public function connect()
    {
        try {
            $this->pdo = new PDO("sqlite:" . ConfigurationsType::$sqliteConnection);
            echo json_encode(
                [
                    "msg" => "connectionn was fully established",
                    "status" => "success",
                ]
            );
        } catch (PDOException $e) {

            echo json_encode(
                [
                    "msg" => "Error to connect to the database, please make sure if is everything okay before initialize the server!",
                    "error" => $e->getMessage(),
                ]
            );
        }
    }

    public function getPdoConnection() {
        return $this->pdo;
    }
}
