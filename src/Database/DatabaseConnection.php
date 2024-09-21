<?php

namespace Backend\Products\Database;

use Backend\Products\Database\Config\ConfigurationsType;
use PDO;
use PDOException;

class DatabaseConnection
{
    private $pdo;
    private static $instance;

    public function connect()
    {
        try {
            $this->pdo = new PDO("sqlite:" . ConfigurationsType::$sqliteConnection, null, null, [PDO::ATTR_PERSISTENT => true]);
            echo json_encode(
                [
                    "msg" => "connectionn was fully established",
                    "status" => "success",
                ]
            );
        } catch (PDOException $e) {
            print_r($e);
            echo json_encode(
                [
                    "msg" => "Error to connect to the database, please make sure if is everything okay before initialize the server!",
                    "error" => $e->getMessage(),
                ]
            );
        }
    }

   
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
