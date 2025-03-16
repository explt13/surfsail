<?php

use Surfsail\models\AppModel;
use Surfsail\models\ArticleModel;
use Surfsail\models\BrandModel;
use Surfsail\models\CartModel;
use Surfsail\models\CategoryModel;
use Surfsail\models\CurrencyModel;
use Surfsail\models\FavoriteModel;
use Surfsail\models\FilterModel;
use Surfsail\models\interfaces\ArticleModelInterface;
use Surfsail\models\interfaces\BrandModelInterface;
use Surfsail\models\interfaces\CartModelInterface;
use Surfsail\models\interfaces\CategoryModelInterface;
use Surfsail\models\interfaces\CurrencyModelInterface;
use Surfsail\models\interfaces\FavoriteModelInterface;
use Surfsail\models\interfaces\FilterModelInterface;
use Surfsail\models\interfaces\OrderModelInterface;
use Surfsail\models\interfaces\ProductModelInterface;
use Surfsail\models\interfaces\ReviewModelInterface;
use Surfsail\models\interfaces\UserModelInterface;
use Surfsail\models\OrderModel;
use Surfsail\models\ProductModel;
use Surfsail\models\ReviewModel;
use Surfsail\models\UserModel;
use Surfsail\providers\CategoryServiceProvider;
use Surfsail\providers\CurrencyServiceProvider;
use Explt13\Nosmi\base\Widget;
use Explt13\Nosmi\interfaces\WidgetInterface;
use Explt13\Nosmi\Cache;
use Explt13\Nosmi\interfaces\CacheInterface;
use Explt13\Nosmi\Container;
use Explt13\Nosmi\interfaces\ContainerInterface;
use Explt13\Nosmi\ControllerResolver;
use Explt13\Nosmi\MiddlewareLoader;
use Explt13\Nosmi\RouteContext;

return [
    // -- providers -- //
    CategoryServiceProvider::class  => fn($container) => $container->autowire(CategoryServiceProvider::class),
    CurrencyServiceProvider::class  => fn($container) => $container->autowire(CurrencyServiceProvider::class),

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