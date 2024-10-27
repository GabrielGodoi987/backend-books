<?php
// página principal e padrão para o desenvolvimento do sistema
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\LogsController;
use Backend\Products\Controller\ProductController;
use Backend\Products\Controller\UserController;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

$connection = new DatabaseConnection();

$products = new ProductController();
$logs = new LogsController();
$user = new UserController();

// products Routes
Router::get('/product', function () use ($products) {
    echo $products->getAllProducts();
});

Router::get('/product/find/{id}', function ($id) use ($products) {
    echo $products->getProductById($id);
});

Router::delete("/product/delete/{id}", function ($id) use ($products) {
    echo $products->deleteProduct($id);
});

Router::post("/product/create", function () use ($products) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $products->createProduct($inputData);
});

Router::put("/product/update/{id}", function ($id) use ($products) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $products->updateProduct($inputData, $id);
});

//logs Routes
Router::get('/logs', function () use ($logs) {
    echo $logs->getAllLogs();
});

Router::get("/logproduct/{id}", function ($id) use ($logs) {
    echo $logs->getLogById($id);
});

Router::resolve();
