<?php
// página principal e padrão para o desenvolvimento do sistema
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\ProductController;
use Backend\Products\Controller\UserController;
use Backend\Products\Model\UserModel;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;

$connection = new DatabaseConnection();

$products = new ProductController();
$user = new UserController();


// user Routes
Router::post("/createUSer", function () use ($user) {
    $userModel = new UserModel();
    $name = json_decode(file_get_contents("php://input"), true);
    $email = json_decode(file_get_contents("php://input"), true);
    $pass = json_decode(file_get_contents("php://input"), true);

    $userModel->setName($name);
    $userModel->setEmail($email);
    $userModel->setPass($pass);
    echo $user->createUser($userModel);
});



// products routes
Router::get('/allProducts', function () use ($products) {
    echo $products->getAllProducts();
});

Router::get('/products/{id}', function ($id) use ($products) {
    echo $id;
});

Router::resolve();
