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

    public function __construct()
    {
        $this->connection =  DatabaseConnection::getInstance();
    }

    public function pdoTest()
    {
        $pdo = DatabaseConnection::getInstance();
        // Agora, você pode executar a query
        if ($pdo) {
            $stmt = $pdo->query("SELECT * FROM table_name");
        } else {
            echo "A conexão com o banco de dados falhou!";
        }
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM Product;";
        try {
            $dataQuery = $this->connection->query($query);
            $data = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($data)) {
                http_response_code(HttpEnum::OK);
                return json_encode(
                    [
                        "msg" => "Dados buscados com sucess",
                        "data" => $data
                    ]
                );
            } else {
                http_response_code(response_code: HttpEnum::NO_CONTENT);
                return json_encode(
                    [
                        "msg" => "Nenhum dado encontrado",
                        "data" => []
                    ]
                );
            }
        } catch (\Throwable $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => $th->getMessage(),
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
