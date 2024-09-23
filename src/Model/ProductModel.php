<?php
// aqui ficará a regra de nedócio e as queries do banco de dados
namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use PDO;
use PDOException;

class ProductModel
{
    private $connection;
    private $table = 'Product';

    public function __construct()
    {
        $this->connection =  DatabaseConnection::getInstance();
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM " . $this->table . ";";
        try {
            $dataQuery = $this->connection->prepare($query);
            $dataQuery->execute();
            $data = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
            if ($dataQuery->rowCount() >= 0) {
                http_response_code(HttpEnum::OK);
                return json_encode(
                    [
                        "msg" => "Dados buscados com sucess",
                        "data" => $data
                    ]
                );
            } 
        } catch (\Throwable $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => $th,
                    "error" => "Server error"
                ]
            );
        }
    }

    public function getProductById($id)
    {
        try {
            http_response_code(HttpEnum::OK);
            return $id;
        } catch (\Throwable $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return $th->getMessage();
        }
    }
}
