<?php
namespace Surfsail\controllers;

use Surfsail\interfaces\ProductModelInterface;
use Explt13\Nosmi\base\Controller;

class SearchController extends Controller
{
    protected $product_model;
    
    public function __construct(ProductModelInterface $product_model)
    {
        $this->product_model = $product_model;
    }
    
    function get()
    {
        header('Content-Type: application/json');
        $query = $this->request->getQueryParams()['query'] ?? "";
        $query = trim($query);
        if ($query === "") {
            $this->response = $this->response->withStatus(400);
            return;
        }
        $result = $this->product_model->getProducts(["LIKE_name" => $query], 5);
        $this->response = $this->response->withStatus(200)->withJson($result);
        return;
    }
}