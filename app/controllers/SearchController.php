<?php
namespace app\controllers;

use app\models\ProductModel;

class SearchController extends AppController
{
    function getAction()
    {
        header('Content-Type: application/json');
        $query = $_GET['query'] ?? null;
        $query = trim($query);
        if ($query !== ""){
            $product_model = new ProductModel();
            $result = $product_model->getProducts(["LIKE_title" => $query], 5);
            http_response_code(200);
            echo json_encode($result);
            return;
        }
        http_response_code(400);
        die;
    }
}