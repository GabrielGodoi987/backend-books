<?php

namespace Backend\Products\Controller;

use Backend\Products\Model\ProductModel;

class ProductController
{
    private $defaultRoute = "/products";
    private $productsModel;
    public function __construct()
    {
        $this->productsModel = new ProductModel();
    }

    public function getDefaultRoute()
    {
        return $this->defaultRoute;
    }

    public function getAllProducts()
    {
        return $this->productsModel->getAllProducts();
    }
}