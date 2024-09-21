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
    private $table = 'Products';
    public function  __construct()
    {
        $connectionDb = new DatabaseConnection();
        $this->connection = $connectionDb->getPdoConnection();
    }
    public function getAllProducts()
    {
        $query = "SELECT * FROM $this->table;";
        try {
            $dataQuery = $this->connection->prepare($query);
            $dataQuery->execute();
            http_response_code(HttpEnum::OK);
            return json_encode(
                [
                    "msg" => "Dados buscados com sucess",
                    "data" => $dataQuery->fetchAll(PDO::FETCH_ASSOC)
                ]
            );
        } catch (\Throwable $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return $th->getMessage();
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
