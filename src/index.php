<?php
// página principal e padrão para o desenvolvimento do sistema
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\ProductController;
use Backend\Products\Controller\UserController;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;

$connection = new DatabaseConnection();

$products = new ProductController();
$user = new UserController();


// user Routes
Router::post("/createUSer", function () use ($user){
  
});



// products routes
Router::get('/allProducts', function () use ($products) {
    echo $products->getAllProducts();
});

Router::get('/products/{id}', function ($id) use ($products) {
    echo $products->getProductById($id);
});

Router::resolve();
