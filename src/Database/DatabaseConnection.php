<?php

namespace Backend\Products\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private $pdo;
    private static $instance;

    public function __construct()
    {
        $config = require __DIR__ . '/ConfigurationsType.php';

        $sqlitePath = $config['sqlite'];

        try {
            $this->pdo = new PDO("sqlite:" . __DIR__ . '/' . $sqlitePath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
