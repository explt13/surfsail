<?php
namespace app\controllers;

use app\models\ProductModel;

abstract class BundleController extends AppController
{
    protected $bundle_model;

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->bundle_model->addProduct($data);

        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function getProductsListAction()
    {
        header('Content-Type: application/json');
        if (isset($_SESSION['user'])) {
            $products_ids = $this->bundle_model->getProductsIds();
        } else {
            $products_ids = [];
        }
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->bundle_model->deleteProduct($data['product_id']);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
}