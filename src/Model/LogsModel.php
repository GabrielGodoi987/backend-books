<?php

namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use PDO;
use PDOException;

class LogsModel
{
    private $conn;
    private $id;
    private $action;
    private $productId;
    public function __construct()
    {
        $this->conn = DatabaseConnection::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getAction()
    {
        return $this->action;
    }
    public function setAction($action)
    {
        $this->action = $action;
    }
    public function getProductId()
    {
        return $this->productId;
    }
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }
    public function getAllLogs()
    {
        $query = "SELECT * FROM Logs LEFT JOIN Product ON Logs.productID == Product.id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode(
                [
                    "msg" => "Log encontrado com sucesso",
                    'data' => $result
                ]
            );
        } catch (PDOException $th) {
            return json_encode(
                [
                    'msg' => "Erro de servidor ao buscar todos os logs, verifique os erros",
                    'error' => $th->getMessage()
                ]
            );
        }
    }
    public function createLog($productData, $action)
    {
        $query = "INSERT INTO Logs(productAction, creationDate, userInsert, productId) VALUES (:productAction, :creationDate, :userInsert, :productId);";
        $idProduct = $productData->getIdProduct();
        $dateTime =  $productData->getDateTime();
        $userInsert = $productData->getUserInsert();
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":productAction", $action);
            $stmt->bindParam(":creationDate", $dateTime);
            $stmt->bindParam(":userInsert", $userInsert);
            $stmt->bindParam(":productId", $idProduct);
            $stmt->execute();
            return $productData;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findLogOfAProduct($productId)
    {
        $query = "SELECT * FROM Logs WHERE Logs.productId = $productId LEFT OUTER JOIN Logs.productID == Product.id";
    }
}
