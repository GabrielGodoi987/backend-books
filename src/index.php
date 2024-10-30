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
Router::post('/createuser', function () use ($user) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $user->createUser($inputData);
});

Router::post('/login', function () use ($user) {
    $inputData = json_decode(file_get_contents("php://input"));
    echo $user->loginUser($inputData);
});

// products Routes
Router::get('/product', function () use ($products, $tokenManager) {
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $products->getAllProducts();
    else
        echo json_encode(["msg" => "Usuário não autenticado"]);
});

Router::get('/product/find/{id}', function ($id) use ($products, $tokenManager) {
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $products->getProductById($id);
});

Router::delete("/product/delete/{id}", function ($id) use ($products, $tokenManager) {
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $products->deleteProduct($id);
    else
        echo json_encode(["msg" => "Usuário não autenticado"]);
});

Router::post("/product/create", function () use ($products, $tokenManager): void {
    $inputData = json_decode(file_get_contents("php://input"));
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $products->createProduct($inputData);
    else
        echo json_encode(["msg" => "Usuário não autenticado"]);
});

Router::put("/product/update/{id}", function ($id) use ($products, $tokenManager) {
    $inputData = json_decode(file_get_contents("php://input"));
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $products->updateProduct($inputData, $id);
    else
        echo json_encode(["msg" => "Usuário não autenticado"]);
});

//logs Routes
Router::get('/logs', function () use ($logs) {
    echo $logs->getAllLogs();
});

Router::get("/logproduct/{id}", function ($id) use ($logs, $tokenManager) {
    $validate = $tokenManager->verifyToken();
    if ($validate)
        echo $logs->getLogById($id);
    else
        echo json_encode(["msg" => "Usuário não autenticado"]);
});

Router::resolve();
