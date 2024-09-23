<?php

namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use PDOException;
class UserModel
{
    private $id;
    private $name;
    private $email;
    private $pass;
    private $creationDate;

    private $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    public function getCreationDate()
    {
        return $this->creationDate = new \DateTime();
    }

    public function createUser(UserModel $user)
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $pass = $user->getPass();
        $query = "INSERT INTO User(name, email, pass, creationDate) VALUES (:name, :email, :pass, :creationDate);";
        try {
            $dataQuery = $this->pdo->prepare($query);
            $dataQuery->bindParam(":name", $name);
            $dataQuery->bindParam(":email", $email);
            $dataQuery->bindParam(":pass", $pass);
            $dataQuery->execute();

            http_response_code(HttpEnum::CREATED);
            echo json_encode(
                [
                    "msg" => "Usuário criado com sucessor",
                    "data" => $dataQuery
                ]
            );
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            echo json_encode(
                [
                    "msg" => $th->getMessage()
                ]
            );
        }
    }

    public function findUserEmail($email)
    {
        return $email;
    }
}
