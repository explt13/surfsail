<?php
namespace Surfsail\controllers;

use Surfsail\interfaces\FavoriteModelInterface;
use Explt13\Nosmi\App;
use Explt13\Nosmi\base\Controller;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;

class FavoriteController extends BaseController
{
    protected $favorite_model;
    private CurrencyService $currency_service;

    public function __construct(
        CurrencyService $currency_service,
        FavoriteModelInterface $favorite_model
    )
    {
        parent::__construct();
        $this->currency_service = $currency_service;
        $this->favorite_model = $favorite_model;
    }
    
    public function indexAction()
    {
        $currency = $this->currency_service->getCurrencyByCookie();
        $products = $this->favorite_model->getItemsFromArray('product');
        $view = $this->getView()
                     ->withDataArray(compact('currency', 'products'))
                     ->withMetaArray([
                        'title' => 'Favorite', 
                        'description' => "User's favorite products page",
                        'keywords' => 'Favorite page, products, like'
                    ])
                    ->render('index');
        $this->response = $this->response->withStatus(200)->withHtml($view);
    }

    public function post()
    {
        $entity = $this->getRoute()->getParam('entity');
        $data = $this->request->getParsedBody();
        $result = $this->favorite_model->addItem($entity, $data['item_id']);
        
        $this->response = $this->response
                               ->withStatus($result['response_code'])
                               ->withJson(['message' => $result['message'], 'action' => $result['action']]);
    }

    public function get()
    {
        $entity = $this->getRoute()->getParam('entity');
        if (isset($_SESSION['favorite'][$entity])) {
            $items_ids = $this->favorite_model->getItemsIds($entity);
        } else {
            $items_ids = [];
        }
        $this->response = $this->response->withStatus(200)->withJson($items_ids);
    }

    public function delete()
    {
        $entity = $this->getRoute()->getParam('entity');
        $item_id = (int) $this->getRoute()->getParam('item_id');
        
        $result = $this->favorite_model->deleteItem($item_id, $entity);
        $this->response = $this->response
                               ->withStatus($result['response_code'])
                               ->withJson(["message" => "Product has been removed"]);
    }
}