<?php
namespace nosmi;

use app\middlewares\AuthMiddleware;
use app\middlewares\interfaces\AuthMiddlewareInterface;
use app\models\AppModel;
use app\models\ArticleModel;
use app\models\BrandModel;
use app\models\CartModel;
use app\models\CategoryModel;
use app\models\CurrencyModel;
use app\models\FavoriteModel;
use app\models\interfaces\ArticleModelInterface;
use app\models\interfaces\BrandModelInterface;
use app\models\interfaces\CartModelInterface;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\FavoriteModelInterface;
use app\models\interfaces\OrderModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\models\interfaces\ReviewModelInterface;
use app\models\interfaces\UserModelInterface;
use app\models\OrderModel;
use app\models\ProductModel;
use app\models\ReviewModel;
use app\models\UserModel;
use nosmi\base\Widget;
use nosmi\base\WidgetFactory;
use nosmi\base\WidgetInterface;

class Container implements ContainerInterface
{
    use SingletonTrait;

    protected array $bindings = array();
    protected array $services = array();

    protected function __construct()
    {
        $this->bindings = array(
            ArticleModelInterface::class    => fn() => $this->autowire(ArticleModel::class),
            BrandModelInterface::class      => fn() => $this->autowire(BrandModel::class),
            CartModelInterface::class       => fn() => $this->autowire(CartModel::class),
            CategoryModelInterface::class   => fn() => $this->autowire(CategoryModel::class),
            CurrencyModelInterface::class   => fn() => $this->autowire(CurrencyModel::class),
            FavoriteModelInterface::class   => fn() => $this->autowire(FavoriteModel::class),
            OrderModelInterface::class      => fn() => $this->autowire(OrderModel::class),
            ProductModelInterface::class    => fn() => $this->autowire(ProductModel::class),
            ReviewModelInterface::class     => fn() => $this->autowire(ReviewModel::class),
            UserModelInterface::class       => fn() => $this->autowire(UserModel::class),
            AuthMiddlewareInterface::class  => fn() => $this->autowire(AuthMiddleware::class),
            AppModel::class                 => fn() => $this->autowire(AppModel::class),
            ContainerInterface::class       => fn() => self::getInstance(),
            CacheInterface::class           => fn() => Cache::getInstance(),
            WidgetInterface::class          => fn() => $this->autowire(Widget::class),
        );
    }

    /** 
    * @param string $id Interface Name
    * @param callable $callback, fn() => new Concrete;
    */
    public function set(string $id, callable $callback): void
    {
        if (!interface_exists($id) && !class_exists($id)) {
            throw new \Exception("Cannot bind non-existent interface or class: $id");
        }
        $this->bindings[$id] = $callback;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }
    
    public function get(string $id): object
    {
        if (isset($this->services[$id])) {
            return $this->services[$id];
        }
        
        if (isset($this->bindings[$id])) {
            if (is_callable($this->bindings[$id])) {
                $this->services[$id] = $this->bindings[$id]();
                return $this->services[$id];
            }
        }
        return $this->autowire($id);
    }
    
    public function autowire(string $service): object
    {
        $reflectorClass = new \ReflectionClass($service);

        if ($reflectorClass->isAbstract()) {
            throw new \Exception("Cannot instantiate abstract class: $service");
        }

        if ($reflectorClass->isInterface()) {
            $reflectionClass = substr($service, 0, -9);
            if (!class_exists($reflectionClass)) {
                throw new \Exception("Class $reflectionClass not found");
            }
        }

        $reflectorConstructor = $reflectorClass->getConstructor();

        if (is_null($reflectorConstructor)) {
            return new $service;
        }

        $constructorArgs = $reflectorConstructor->getParameters();
        if (empty($constructorArgs)){
            return new $service;
        }

        $dependencies = [];
        foreach ($constructorArgs as $arg) {
            $argType = $arg->getType();
            if ($argType === null) {
                throw new \Exception("Unable to resolve argument '{$arg->getName()}' for service '$service'");
            }
            if (!class_exists($argType->getName()) && !interface_exists($argType->getName())) {
                throw new \Exception("Parameter '{$arg->getName()}' is not a class or interface");
            }
            $dependencies[$arg->getName()] = $this->get($argType->getName());
        }
        return new $service(...$dependencies);
    }
}
