<?php
// recebe os dados e enviaos para o controller
// após isso deve retornar uma resposta para o usuário
namespace Backend\Products\Controller;

use Backend\Products\Enum\LogsEnum;
use Backend\Products\Model\LogsModel;
use Backend\Products\Model\ProductModel;

class ProductController
{
    private $defaultRoute = "/products";
    private $productsModel;
    private $logsModel;
    public function __construct()
    {
        $this->productsModel = new ProductModel();
        $this->logsModel = new LogsModel();
    }

    public function getDefaultRoute()
    {
        return $this->defaultRoute;
    }

    public function getAllProducts()
    {
        return $this->productsModel->getAllProducts();
    }

    public function getProductById($id)
    {
        return $this->productsModel->getProductById($id);
    }

    public function createProduct($product)
    {
        $this->productsModel->setName($product->name);
        $this->productsModel->setDescription($product->description);
        $this->productsModel->setPrice($product->price);
        $this->productsModel->setStock($product->stock);
        $this->productsModel->setUserInsert($product->userInsert);

        $createProductResult = $this->productsModel->createProduct($this->productsModel);

        if ($createProductResult)
            $createLog = $this->logsModel->createLog($this->productsModel, LogsEnum::CREATION);
        if ($createLog)
            echo $createProductResult;
        else
            echo json_encode(["msg" => "Erro ao cadastrar novo produto"]);
    }

    public function updateProduct($product, $id)
    {
        $this->productsModel->setName($product->name);
        $this->productsModel->setDescription($product->description);
        $this->productsModel->setPrice($product->price);
        $this->productsModel->setStock($product->stock);
        $this->productsModel->setUserInsert($product->userInsert);
        $createLog = $this->logsModel->createLog($this->productsModel, LogsEnum::UPDATE);
        if ($createLog)
            echo $this->productsModel->updateProduct($this->productsModel, $id);
        else
            echo json_encode(["msg" => "Erro ao criar log", "err" => $createLog]);
    }
    public function deleteProduct($id) {}

    public function findProductByName() {}
}
