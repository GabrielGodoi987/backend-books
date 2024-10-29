<?php

namespace Backend\Products\Model;

use Backend\Products\Database\DatabaseConnection;
use Backend\Products\Enum\HttpEnum;
use Backend\Products\Enum\UsersRoleEnum;
use DateTime;
use PDOException;
use PDO;

class UserModel
{
    private $id;
    private $name;
    private $email;
    private $pass;
    private $userRole;
    private $isVerified;
    private $creationDate;
    private $table = "Users";

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
    public function getRole()
    {
        return $this->userRole;
    }
    public function setRole($userRole)
    {
        $this->userRole = $userRole;
    }
    public function getIsVerified()
    {
        return $this->isVerified;
    }
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function setPass($pass)
    {
        $this->pass = password_hash($pass,  PASSWORD_DEFAULT);
    }

    public function createUser(UserModel $user)
    {
        $date = (new DateTime())->format('Y-m-d H:i:s');
        $name = $user->getName();
        $email = $user->getEmail();
        $pass = $user->getPass();
        $role = $user->getRole();
        $isVerified = $user->getIsVerified();

        $query = "INSERT INTO $this->table(name, email, pass, userRole, isVerified, creationDate) VALUES (:name, :email, :pass, :userRole, :isVerified, :creationDate);";
        try {
            $dataQuery = $this->pdo->prepare($query);
            $dataQuery->bindParam(":name", $name);
            $dataQuery->bindParam(":email", $email);
            $dataQuery->bindParam(":pass", $pass);
            $dataQuery->bindParam(":userRole", $role);
            $dataQuery->bindParam(":isVerified", $isVerified);
            $dataQuery->bindParam(":creationDate", $date);
            $dataQuery->execute();

            $data = $dataQuery->fetch(PDO::FETCH_ASSOC);

            if (empty($name) && empty($email) && empty($pass)) {
                http_response_code(HttpEnum::USERERROR);
                echo json_encode(
                    [
                        "msg" => "Preencha todos os dados para cadastras-se",
                        "fields" => "$name $email $pass estão vazios"
                    ]
                );
                return;
            } else {
                http_response_code(HttpEnum::CREATED);
                echo json_encode(
                    [
                        "msg" => "Usuário criado com sucessor",
                        "data" => $data
                    ]
                );
            }
        } catch (PDOException $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            echo json_encode(
                [
                    "msg" => $th->getMessage(),
                    "query" => $query
                ]
            );
        }
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT name, email, pass, userRole FROM Users WHERE Users.email == :email LIMIT 1;";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }
    public function getUserById($id)
    {
        $query = "SELECT * FROM $this->table WHERE $this->table.id == :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM $this->table";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $th) {
            return $th->getMessage();
        }
    }

    public function updateUser($id, $data){}
    public function deleteUser($id){}
}
