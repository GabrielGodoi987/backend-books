<?php

namespace Backend\Products\Controller;

use Backend\Products\Enum\HttpEnum;
use Backend\Products\Model\UserModel;

class UserController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }


    public function createUser($data)
    {
        try {
            if (!isset($data->name, $data->email, $data->pass)) {
                http_response_code(HttpEnum::USERERROR);
                return json_encode(
                    [
                        "msg" => "Dados incompletos",
                        "inputs" => [$data->name, $data->email, $data->pass]
                    ]
                );
            }
            $this->userModel->setName($data->name);
            $this->userModel->setEmail($data->email);
            $this->userModel->setPass($data->pass);
            http_response_code(HttpEnum::OK);
            return $this->userModel->createUser($this->userModel);
        } catch (\Throwable $th) {
            return json_encode(
                [
                    "msg" => "erro de servidor",
                    "error" => $th->getMessage()
                ]
            );
        }
    }

    public function findUserEmail(UserModel $data)
    {
        return $this->userModel->findUserEmail($data);
    }
}
