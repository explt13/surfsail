<?php
namespace Surfsail\controllers;

use Surfsail\models\interfaces\ProductModelInterface;
use Explt13\Nosmi\base\Controller;

class SearchController extends Controller
{
    protected $product_model;
    
    public function __construct(ProductModelInterface $product_model)
    {
        $this->product_model = $product_model;
    }
    
    function getAction()
    {
        header('Content-Type: application/json');
        $query = $_GET['query'] ?? null;
        $query = trim($query);
        if ($query !== ""){
            $result = $this->product_model->getProducts(["LIKE_name" => $query], 5);
            http_response_code(200);
            echo json_encode($result);
            return;
        }
        http_response_code(400);
    }
}