<?php
// página principal e padrão para o desenvolvimento do sistema
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\LogsController;
use Backend\Products\Controller\ProductController;
use Backend\Products\Controller\UserController;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;

$connection = new DatabaseConnection();

$products = new ProductController();
$logs = new LogsController();
$user = new UserController();

// products Routes
Router::get('/produtos', function () use ($products) {
    echo $products->getAllProducts();
});

Router::get('/products/find/{id}', function ($id) use ($products) {
    echo $products->getProductById($id);
});

Router::delete("/products/delete/{id}", function ($id) use ($products) {
    echo $id;
});

Router::post("/produtos/create", function () use ($products) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $products->createProduct($inputData);
});

Router::put("/products/update", function () use ($products) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $products->updateProduct($inputData);
});

//logs Routes
Router::get('/alllogs', function () use ($logs) {
    echo $logs->getAllLogs();
});

Router::resolve();
