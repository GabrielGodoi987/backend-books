<?php

namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use Backend\Products\Enum\LogsEnum;
use DateTime;
use PDO;
use PDOException;

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
    private $logsModel;

    public function __construct()
    {
        $this->connection =  DatabaseConnection::getInstance();
        $this->logsModel = new LogsModel();
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

    public function getDateTime()
    {
        return (new DateTime())->format('Y-m-d H:i:s');
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM $this->table WHERE Product.isActive == 1;";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(HttpEnum::OK);
            return json_encode($data);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro ao buscar produtos",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM Product WHERE id = :id";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(HttpEnum::OK);
            return json_encode($data);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro ao buscar produto",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function findProductByName($name)
    {
        $query = "SELECT * FROM Product WHERE name = :name";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro ao buscar produto",
                "error" => $e->getMessage()
            ]);
        }
    }
    public function createProduct($data)
    {
        $name = $data->getName();
        $description = $data->getDescription();
        $price = $data->getPrice();
        $stock = $data->getStock();
        $date_time = $data->getDateTime();
        $userInsert = $data->getUserInsert();
        $createValue = 1;

        if (empty($name) && strlen($name) < 3 && empty($description) && empty($price) && empty($stock) && empty($userInsert)) {
            http_response_code(HttpEnum::USERERROR);
            return json_encode(["msg" => "Dados insuficientes"]);
        }

        $existingProduct = $this->findProductByName($name);
        if ($existingProduct) {
            http_response_code(HttpEnum::USERERROR);
            return json_encode([
                "msg" => "O produto já existe",
                "data" => $existingProduct
            ]);
        }

        $query = "INSERT INTO Product (name, description, price, stock, userInsert, date_time, isActive) 
                  VALUES (:name, :description, :price, :stock, :userInsert, :date_time, :isActive)";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':userInsert', $userInsert);
            $stmt->bindParam(':date_time', $date_time);
            $stmt->bindParam(":isActive", $createValue);
            $stmt->execute();

            $productId = $this->connection->lastInsertId();

            $this->logsModel->createLog($data, $productId, LogsEnum::CREATION);

            return $this->getProductById($productId);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro de servidor ao criar produto",
                "error" => $e->getMessage()
            ]);
        }
    }


    public function updateProduct($data, $id)
    {
        $name = $data->getName();
        $description = $data->getDescription();
        $price = $data->getPrice();
        $stock = $data->getStock();
        $userInsert = $data->getUserInsert();
        $date_time = $data->getDateTime();

        if (empty($name) || empty($description) || empty($price) || empty($stock) || empty($userInsert)) {
            http_response_code(HttpEnum::USERERROR);
            return json_encode(["msg" => "Dados insuficientes"]);
        }

        $query = "UPDATE Product 
                  SET name = :name, description = :description, price = :price, 
                      stock = :stock, userInsert = :userInsert, date_time = :date_time 
                  WHERE id = :id";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':userInsert', $userInsert);
            $stmt->bindParam(':date_time', $date_time);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->logsModel->createLog($data, $id, LogsEnum::UPDATE);

            return $this->getProductById($id);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro ao atualizar produto",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function deleteProductById($id)
    {
        $query = "UPDATE Product SET isActive = 0 WHERE Product.id = :id";
        try {
            $findProduct = json_decode($this->getProductById($id), true);

            if (!$findProduct || empty($findProduct['data'])) {
                http_response_code(HttpEnum::USERERROR);
                return json_encode([
                    "msg" => "O produto não existe em nossa base de dados",
                ]);
            }

            $product = new ProductModel();
            $product->setUserInsert($findProduct['data']['userInsert']);

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();
       
            $this->logsModel->createLog($product, $id, LogsEnum::DELETE);

            http_response_code(HttpEnum::OK);
            return json_encode([
                "msg" => "Produto deletado com sucesso",
                "userInsert" => $findProduct
            ]);
        } catch (PDOException $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro ao deletar produto",
                "error" => $e->getMessage()
            ]);
        }
    }
}
