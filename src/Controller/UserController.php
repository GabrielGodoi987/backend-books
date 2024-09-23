<?php

namespace Backend\Products\Controller;
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
        return $this->userModel->createUser($data);
    }

    public function findUserEmail(UserModel $data)
    {
      return $this->userModel->findUserEmail($data);
    }
}
