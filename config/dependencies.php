<?php

use app\models\AppModel;
use app\models\ArticleModel;
use app\models\BrandModel;
use app\models\CartModel;
use app\models\CategoryModel;
use app\models\CurrencyModel;
use app\models\FavoriteModel;
use app\models\FilterModel;
use app\models\interfaces\ArticleModelInterface;
use app\models\interfaces\BrandModelInterface;
use app\models\interfaces\CartModelInterface;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\FavoriteModelInterface;
use app\models\interfaces\FilterModelInterface;
use app\models\interfaces\OrderModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\models\interfaces\ReviewModelInterface;
use app\models\interfaces\UserModelInterface;
use app\models\OrderModel;
use app\models\ProductModel;
use app\models\ReviewModel;
use app\models\UserModel;
use app\providers\CategoryServiceProvider;
use app\providers\CurrencyServiceProvider;
use nosmi\base\Widget;
use nosmi\interfaces\WidgetInterface;
use nosmi\Cache;
use nosmi\interfaces\CacheInterface;
use nosmi\Container;
use nosmi\interfaces\ContainerInterface;
use nosmi\ControllerResolver;
use nosmi\MiddlewareLoader;
use nosmi\RouteContext;

return [
    // -- providers -- //
    CategoryServiceProvider::class  => fn($container) => $container->autowire(CategoryServiceProvider::class),
    CurrencyServiceProvider::class => fn($container) => $container->autowire(CurrencyServiceProvider::class),

    // -- models -- //
    ArticleModelInterface::class    => fn($container) => $container->autowire(ArticleModel::class),
    BrandModelInterface::class      => fn($container) => $container->autowire(BrandModel::class),
    CartModelInterface::class       => fn($container) => $container->autowire(CartModel::class),
    CategoryModelInterface::class   => fn($container) => $container->autowire(CategoryModel::class),
    CurrencyModelInterface::class   => fn($container) => $container->autowire(CurrencyModel::class),
    FavoriteModelInterface::class   => fn($container) => $container->autowire(FavoriteModel::class),
    OrderModelInterface::class      => fn($container) => $container->autowire(OrderModel::class),
    ProductModelInterface::class    => fn($container) => $container->autowire(ProductModel::class),
    ReviewModelInterface::class     => fn($container) => $container->autowire(ReviewModel::class),
    UserModelInterface::class       => fn($container) => $container->autowire(UserModel::class),
    AppModel::class                 => fn($container) => $container->autowire(AppModel::class),
    FilterModelInterface::class     => fn($container) => $container->autowire(FilterModel::class),
    RouteContext::class             => fn($container) => $container->autowire(RouteContext::class),
    
    // -- core -- //
    ContainerInterface::class       => fn($container) => Container::getInstance(),
    CacheInterface::class           => fn($container) => Cache::getInstance(),
    WidgetInterface::class          => fn($container) => $container->autowire(Widget::class),
    ControllerResolver::class       => fn($container) => $container->autowire(ControllerResolver::class),
    MiddlewareLoader::class         => fn($container) => $container->autowire(MiddlewareLoader::class),
];