<?php
// página principal e padrão para o desenvolvimento do sistema
namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\ProductController;
use Backend\Products\Routes\Router;
use Backend\Products\Database\DatabaseConnection;

$connection = new DatabaseConnection();

// rota para testar conexão com o banco de dados
Router::get('/connect', function () use ($connection) {
    echo $connection->connect();
});

$products = new ProductController();

Router::get('/allProducts', function () use ($products) {
    echo $products->getAllProducts();
});

Router::get('/products/{id}', function ($id) use ($products) {
    echo $products->getProductById($id);
});

Router::resolve();
