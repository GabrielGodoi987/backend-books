<?php
// recebe os dados e enviaos para o controller
// após isso deve retornar uma resposta para o usuário
namespace Backend\Products\Controller;

use Backend\Products\Database\DatabaseConnection;
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

    public function getProductById($id){
        return $this->productsModel->getProductById($id);
    }
}