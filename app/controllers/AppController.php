<?php
namespace app\controllers;

use app\middlewares\AuthMiddleware;
use app\middlewares\interfaces\AuthMiddlewareInterface;
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
use nosmi\ContainerInterface;

class AppController extends Controller
{
    protected $currency_model;
    protected $auth_middleware;
    protected $category_model; 
    protected $app_model;

    public function __construct(ContainerInterface $container)
    {
        $this->app_model = $container->get(AppModel::class);
        $this->auth_middleware = $container->get(AuthMiddlewareInterface::class);
        $this->currency_model = $container->get(CurrencyModelInterface::class);
        $this->category_model = $container->get(CategoryModelInterface::class);

        
        App::$registry->setProperty('cart_items_qty', AuthMiddleware::isLoggedIn() ? Cart::getCartQty() : 0);
        App::$registry->setProperty('currencies', $this->currency_model->getCurrencies());
        App::$registry->setProperty('currency', CurrencyModel::getCurrencyByCookie(App::$registry->getProperty('currencies')));
        $this->category_model->getCategories();
    }
}