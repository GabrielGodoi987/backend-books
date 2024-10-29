<?php
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\LogsController;
use Backend\Products\Controller\ProductController;
use Backend\Products\Controller\UserController;
use Backend\Products\JWT\TokenManager;
use Backend\Products\Middleware\TokenMiddleware;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;
use Exception;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json');
$connection = new DatabaseConnection();
$manager = new TokenManager();
$middlewareToken = new TokenMiddleware();
$products = new ProductController();
$logs = new LogsController();
$user = new UserController();

$tokenManager = new TokenManager();

// users Routes
Router::post('/createuser', function() use($user){
    $inputData = json_decode(file_get_contents("php://input"));
    echo $user->createUser($inputData);
});

Router::post('/login', function () use ($user){
    $inputData = json_decode(file_get_contents("php://input"));
    echo $user->loginUser($inputData);
});

Router::post("/test", function() use($user, $tokenManager) {
    $validate = $tokenManager->verifyToken();
    try{
        if ($validate) {
            echo $validate;
        }
    }catch(Exception $e){
        echo $e;
    }
});



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
