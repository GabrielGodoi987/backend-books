<?php
// aqui ficará a regra de nedócio e as queries do banco de dados
namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use DateTime;
use PDO;
use PDOException;
use PDORow;

class ProductModel
{
    private $connection;
    private $table = 'Product';

    private $idProduct;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $userInsert;
    private $date_time;

    private $logsController;

    public function __construct()
    {
        $this->connection =  DatabaseConnection::getInstance();
    }

    public function getIdProduct()
    {
        return $this->idProduct;
    }
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function getStock()
    {
        return $this->stock;
    }
    public function setStock($stock)
    {
        $this->stock = $stock;
    }
    public function getUserInsert()
    {
        return $this->userInsert;
    }
    public function setUserInsert($userInsert)
    {
        $this->userInsert = $userInsert;
    }
    public function getAllProducts()
    {
        $query = "SELECT * FROM $this->table;";
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
        } catch (PDOException $th) {
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

        $query = "SELECT * FROM Product WHERE Product.id == $id";
        try {
            $dataquery = $this->connection->prepare($query);
            $dataquery->execute();
            $data = $dataquery->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(HttpEnum::OK);
            return json_encode(
                [
                    "msg" => "Produto encontrado com sucesso",
                    "data" => $data

                ]
            );
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "Erro ao buscar produtos",
                    "error" => $th->getMessage()
                ]
            );
        }
    }

    public function findProductByName($name)
    {
        $query = "SELECT * FROM Product WHERE Product.name == $name;";
        try {
            if (!empty($id)) {
                $dataQuery = $this->connection->prepare($query);
                $dataQuery->execute();

                return json_encode(
                    [
                        "msg" => "Produto encontrado com sucesso",
                        "data" => $dataQuery
                    ]
                );
            }
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "O servidor não conseguiu buscar o produto",
                    "error" => $e
                ]
            );
        }
    }

    public function createProduct($data)
    {
        $name = $data->getName();
        $description = $data->getDescription();
        $price = $data->getPrice();
        $stock = $data->getStock();
        $date_time = (new DateTime())->format('Y-m-d H:i:s');
        $userInsert = $data->getUserInsert();
        $query = "INSERT INTO Product(name, description, price, stock, date_time, userInsert) VALUES (:name, :description, :price, :stock, :date_time, :userInsert)";
        try {
            // faz as devidas verificações
            if (!isset($name, $description, $price, $stock, $date_time, $userInsert) && strlen($name) > 3 && $stock > 0) {
                $ProductExists = $this->findProductByName($name);

                // se o usuário existir ele vai dar erro!
                if ($ProductExists) {
                    http_response_code(HttpEnum::SERVER_ERROR);
                    return json_encode(
                        [
                            "msg" => "O produto $name já existe",
                            "data" => $ProductExists
                        ]
                    );
                }
                $dataQuery = $this->connection->prepare($query);
                $dataQuery->bindParam(":name", $name);
                $dataQuery->bindParam(":description", $description);
                $dataQuery->bindParam(":price", $price);
                $dataQuery->bindParam(":stock", $stock);
                $dataQuery->bindParam(":date_time", $date_time);
                $dataQuery->bindParam(":userInsert", $userInsert);
                $dataQuery->execute();
                http_response_code(HttpEnum::CREATED);
                return json_encode(
                    [
                        "msg" => "Produto criado com sucesso",
                        "data" => $dataQuery
                    ]
                );
            }
            http_response_code(HttpEnum::USERERROR);
            return json_encode(
                [
                    "msg" => "Dados incompletos"
                ]
            );
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "Erro de servidor ao criar novo produto",
                    "error" => $th->getMessage()
                ]
            );
        }
    }

    public function updateProduct($data)
    {
        $query = "UPDATE Product SET name = :name, description = :description, price = :price, stock = :stock, userInsert = :userInsert, date_time = :date_time";
        try {
            if (!isset($data)) {
                $query = $this->connection->prepare($query);
                $query->execute($data);
            }
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "Erro de servidor ao atualizar produto",
                    "error"=> $e->getMessage()
                ]
            );
        }
    }
    public function deleteProductById($id)
    {
        $query = "DELETE FROM Product where Product.id == $id";
        try {
            if (!empty($id)) {
                $query = $this->connection->prepare($query);
                $query->execute();
            }
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "Erro ao atualizar produto"
                ]
            );
        }
    }
    public function deleteProductByName($name)
    {
        $query = "DELETE FROM Product where Product.name == $name";
        try {
            $query = $this->connection->prepare($query);
            $query->execute();
            return json_encode(
                [
                    "msg" => "Produto deletado com sucesso"
                ]
            );
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode(
                [
                    "msg" => "Erro de servidor ao tentar deletar produto",
                    "err" => $th->getMessage()
                ]
            );
        }
    }
}
