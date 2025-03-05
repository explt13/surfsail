<?php
namespace app\controllers;

use app\models\interfaces\FavoriteModelInterface;
use nosmi\App;
use nosmi\base\Controller;

class FavoriteController extends Controller
{
    protected $favorite_model;

    public function __construct(FavoriteModelInterface $favorite_model)
    {
        $this->favorite_model = $favorite_model;
    }
    
    public function indexAction()
    {
        $currency = App::$registry->getProperty('currency');
        $products = $this->favorite_model->getItemsFromArray();
        http_response_code(200);
        $this->setMeta("Favorite", "User's favorite products page", 'Favorite page, products, like');
        $this->render(data: compact('currency', 'products'));
    }

    public function addAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->favorite_model->addItem($data);

        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function getAddedItemsAction()
    {
        header('Content-Type: application/json');
        if (isset($_SESSION['user'])) {
            $products_ids = $this->favorite_model->getItemsIds();
        } else {
            $products_ids = [];
        }
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->favorite_model->deleteItem($data['item_id']);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
}