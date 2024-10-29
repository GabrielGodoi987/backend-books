<?php

namespace Backend\Products\Controller;

use Backend\Products\Enum\HttpEnum;
use Backend\Products\Enum\UsersRoleEnum;
use Backend\Products\JWT\TokenManager;
use Backend\Products\Model\UserModel;
use Exception;

// precisamos fazer o login de usuários
// precisamos fazer o crud de usuários no backend

class UserController
{
    private $userModel;
    private $tokenManager;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenManager = new TokenManager();
    }

    public function createUser($data)
    {
        try {
            if (!isset($data->name, $data->email, $data->pass, $data->userRole)) {
                http_response_code(HttpEnum::USERERROR);
                return json_encode([
                    "msg" => "Dados incompletos",
                    "inputs" => [$data->name, $data->email, $data->pass]
                ]);
            }

            $validRoles = array_map(fn($role) => $role->value, UsersRoleEnum::cases());
            if (!in_array($data->userRole, $validRoles)) {
                http_response_code(HttpEnum::USERERROR);
                return json_encode([
                    "msg" => "O papel enviado não existe",
                ]);
            }

            $this->userModel->setName($data->name);
            $this->userModel->setEmail($data->email);
            $this->userModel->setPass($data->pass);
            $this->userModel->setRole($data->userRole);
            $this->userModel->setIsVerified($data->isVerified ?? false);

            http_response_code(HttpEnum::OK);
            return $this->userModel->createUser($this->userModel);
        } catch (Exception $e) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return json_encode([
                "msg" => "Erro de servidor",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function deleteUser($id)
    {
    }
    public function updateUSer($id, $data)
    {
    }
    public function verifyEmail($user)
    {
    }
    public function loginUser($data)
    {
        $userExists = $this->userModel->getUserByEmail($data->email);
        if ($userExists && password_verify($data->pass, $userExists['pass'])) {
            $jwToken = $this->tokenManager->createToken($userExists);
            return json_encode([
                'msg' => 'Usuário logado',
                "user" => [
                    "name" => $userExists["name"],
                    "email" => $userExists["email"],
                    'userRole' => $userExists['userRole'],
                    "token" => $jwToken
                ]
            ]);
        } else {
            return json_encode([
                'msg' => "Usuário não encontrado"
            ]);
        }
    }
}
