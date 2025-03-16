<?php
namespace Surfsail\controllers;

use Surfsail\models\interfaces\FavoriteModelInterface;
use Explt13\Nosmi\App;
use Explt13\Nosmi\base\Controller;

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
        $products = $this->favorite_model->getItemsFromArray('product');
        http_response_code(200);
        $this->setMeta("Favorite", "User's favorite products page", 'Favorite page, products, like');
        $this->render(data: compact('currency', 'products'));
    }

    public function addAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->favorite_model->addItem($data);
        
        header('Content-Type: application/json');
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function getAddedItemsAction()
    {
        $entity = $this->route->entity;
        if (isset($_SESSION['user'])) {
            $products_ids = $this->favorite_model->getItemsIds($entity);
        } else {
            $products_ids = [];
        }
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($products_ids);
    }

    public function deleteAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->favorite_model->deleteItem($data, $this->route->entity);
        http_response_code($result['response_code']);
        echo json_encode(["message" => "Product has been removed"]);
    }
}