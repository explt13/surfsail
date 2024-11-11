<?php
namespace app\controllers;

use app\models\ProductModel;

class BundleController extends AppController
{
    private string $controller_model;
    private string $controller_name_lc;
    public function __construct($route)
    {
        parent::__construct($route);
        $this->controller_name_lc = strtolower($route['controller']);
        $this->controller_model = "app\\models\\" . $route['controller'] . 'Model';
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $bundle_model = new $this->controller_model($this->controller_name_lc);
        $result = $bundle_model->addProduct($data);
        
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function getProductsListAction()
    {
        header('Content-Type: application/json');
        if (isset($_SESSION['user'])) {
            $bundle_model = new $this->controller_model($this->controller_name_lc);
            $products_ids = $bundle_model->getProductsIds();
        } else {
            $products_ids = [];
        }
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $bundle_model = new $this->controller_model($this->controller_name_lc);
        $result = $bundle_model->deleteProduct($data['product_id']);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
}