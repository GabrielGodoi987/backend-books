<?php

namespace Backend\Products;

require "../vendor/autoload.php";

use Backend\Products\Controller\ProductController;
use Backend\Products\Routes\Router;

$products = new ProductController();

Router::get('/hello', function () use ($products) {
    echo $products->getAllProducts();
});

Router::resolve();
