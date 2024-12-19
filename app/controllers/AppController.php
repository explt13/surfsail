<?php
namespace app\controllers;

use app\middlewares\AuthMiddleware;
use nosmi\base\Controller;
use app\models\AppModel;
use app\models\CartModel;
use app\models\CategoryModel;
use app\models\CurrencyModel;
use app\models\FavoriteModel;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\widgets\cart\Cart;
use nosmi\App;
use nosmi\Cache;

class AppController extends Controller
{
    protected $currency_model;
    protected $auth_middleware;
    protected $category_model; 

    public function __construct(CurrencyModelInterface $currency_model, AuthMiddleware $auth_middleware, CategoryModelInterface $category_model, array $route)
    {
        parent::__construct($route);
        new AppModel();

        $this->auth_middleware = $auth_middleware;
        $this->currency_model = $currency_model;
        $this->category_model = $category_model;

        $this->auth_middleware->CheckAuth($route);

        
        App::$registry->setProperty('cart_items_qty', AuthMiddleware::isLoggedIn() ? Cart::getCartQty() : 0);
        App::$registry->setProperty('currencies', $this->currency_model->getCurrencies());
        App::$registry->setProperty('currency', CurrencyModel::getCurrencyByCookie(App::$registry->getProperty('currencies')));
        $this->category_model->getCategories();
    }
}