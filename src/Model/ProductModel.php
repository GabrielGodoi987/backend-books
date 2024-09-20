<?php

namespace Backend\Products\Model;

use Backend\Products\Enum\HttpEnum;

class ProductModel
{
    public function getAllProducts()
    {
        try {
            http_response_code(HttpEnum::OK);
            return "Buscar todos os dados do banco de dados";
        } catch (\Throwable $th) {
            http_response_code(HttpEnum::SERVER_ERROR);
            return $th->getMessage();
        }
    }
}
