<?php
namespace nosmi;

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

class Container
{
    use SingletonTrait;

    protected array $services = array();


    protected function __construct()
    {
        $this->services = array(
            ArticleModelInterface::class     => fn() => $this->make(ArticleModel::class),
            BrandModelInterface::class       => fn() => $this->make(BrandModel::class),
            CartModelInterface::class        => fn() => $this->make(CartModel::class),
            CategoryModelInterface::class    => fn() => $this->make(CategoryModel::class),
            CurrencyModelInterface::class    => fn() => $this->make(CurrencyModel::class),
            FavoriteModelInterface::class    => fn() => $this->make(FavoriteModel::class),
            OrderModelInterface::class       => fn() => $this->make(OrderModel::class),
            ProductModelInterface::class     => fn() => $this->make(ProductModel::class),
            ReviewModelInterface::class      => fn() => $this->make(ReviewModel::class),
            UserModelInterface::class        => fn() => $this->make(UserModel::class),
        );
    }

    /** 
    * @param string $id Interface Name
    * @param callable $callback, fn() => new Class;
    */
    public function set(string $id, callable $callback)
    {
        $this->services[$id] = $callback;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
    
    public function get(string $id): mixed
    {
        if (isset($this->services[$id])) {
            if (is_object($this->services[$id])) {
                return $this->services[$id];
            }
    
            if (is_callable($this->services[$id])) {
                $this->services[$id] = $this->services[$id]();
                return $this->services[$id];
            }
        }
        return $this->make($id);
    }
    
    public function make(string $service)
    {
        $reflectorClass = new \ReflectionClass($service);

        if ($reflectorClass->isAbstract()) {
            throw new \Exception("Cannot instantiate abstract class or interface: $service");
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
            $dependencies[$arg->getName()] = $this->get($argType->getName());
        }

        return new $service(...$dependencies);
    }
}
