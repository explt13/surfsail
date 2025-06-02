<?php

use Surfsail\controllers\AuthController;
use Surfsail\controllers\CartController;
use Surfsail\controllers\CatalogController;
use Surfsail\controllers\CurrencyController;
use Surfsail\controllers\FavoriteController;
use Surfsail\controllers\MainController;
use Surfsail\controllers\ProductController;
use Surfsail\controllers\SearchController;
use Surfsail\controllers\UserController;
use Surfsail\models\AppModel;
use Surfsail\models\ArticleModel;
use Surfsail\models\BrandModel;
use Surfsail\models\CartModel;
use Surfsail\models\CategoryModel;
use Surfsail\models\CurrencyModel;
use Surfsail\models\FavoriteModel;
use Surfsail\models\FilterModel;
use Surfsail\interfaces\ArticleModelInterface;
use Surfsail\interfaces\BrandModelInterface;
use Surfsail\interfaces\CartModelInterface;
use Surfsail\interfaces\CategoryModelInterface;
use Surfsail\interfaces\CurrencyModelInterface;
use Surfsail\interfaces\FavoriteModelInterface;
use Surfsail\interfaces\FilterModelInterface;
use Surfsail\interfaces\OrderModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Surfsail\interfaces\ReviewModelInterface;
use Surfsail\interfaces\UserModelInterface;
use Surfsail\models\OrderModel;
use Surfsail\models\ProductModel;
use Surfsail\models\ReviewModel;
use Surfsail\models\UserModel;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;
use Surfsail\widgets\Cart;
use Surfsail\widgets\Menu;

return [

    // -- controllers -- //
    AuthController::class           => AuthController::class,
    CartController::class           => CartController::class,
    CatalogController::class        => CatalogController::class,
    CurrencyController::class       => CurrencyController::class,
    FavoriteController::class       => FavoriteController::class,
    ProductController::class        => ProductController::class,
    SearchController::class         => SearchController::class,
    UserController::class           => UserController::class,
    MainController::class           => MainController::class,

    // -- models -- //
    ArticleModelInterface::class    => ArticleModel::class,
    BrandModelInterface::class      => BrandModel::class,
    CartModelInterface::class       => CartModel::class,
    CategoryModelInterface::class   => CategoryModel::class,
    CurrencyModelInterface::class   => CurrencyModel::class,
    FavoriteModelInterface::class   => FavoriteModel::class,
    OrderModelInterface::class      => OrderModel::class,
    ProductModelInterface::class    => ProductModel::class,
    ReviewModelInterface::class     => ReviewModel::class,
    UserModelInterface::class       => UserModel::class,
    AppModel::class                 => AppModel::class,
    FilterModelInterface::class     => FilterModel::class,

    // -- services -- //
    CurrencyService::class          => CurrencyService::class,
    CategoryService::class          => CategoryService::class,

    // -- widgets -- //
    Menu::class                     => Menu::class,
    Cart::class                     => Cart::class,
];