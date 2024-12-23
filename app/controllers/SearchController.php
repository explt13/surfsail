<?php
namespace app\controllers;

use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\ProductModelInterface;

class SearchController extends AppController
{
    protected $product_model;
    
    public function __construct(
        ProductModelInterface $product_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($currency_model, $category_model);
        $this->product_model = $product_model;
    }
    
    function getAction()
    {
        header('Content-Type: application/json');
        $query = $_GET['query'] ?? null;
        $query = trim($query);
        if ($query !== ""){
            $result = $this->product_model->getProducts(["LIKE_title" => $query], 5);
            http_response_code(200);
            echo json_encode($result);
            return;
        }
        http_response_code(400);
        die;
    }
}