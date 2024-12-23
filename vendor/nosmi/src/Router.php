<?php
namespace nosmi;

use nosmi\base\Controller;
use nosmi\base\WidgetInterface;

class Router
{
    private static array $routes = [];
    private static array $route = [];
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute()
    {
        return self::$route;
    }
    
    public function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if ($this->routeExists($url)) {
            $controller = "app\\controllers\\" . self::$route['prefix'] . self::$route['controller'] . 'Controller'; // admin prefix
            $this->container->set(RouteContext::class, fn() => new RouteContext(self::$route));

            $controllerObject = $this->container->get($controller);
            
            $action = $this->lowerCamelCase(self::$route['action']) . "Action";
            if (method_exists($controllerObject, $action)) {
                $controllerObject->init($this->container->get(RouteContext::class)); // implement proxy   
                $controllerObject->$action();
            } else {
                throw new \Exception("Action: $controller::$action not found", 404);
            }
            
        } else {
            throw new \Exception("Page not found", 404);
        }
    }
    private function routeExists($url)
    {
        foreach (self::$routes as $regexp => $route){
            if (preg_match("#{$regexp}#", $url, $matches)){
                foreach($matches as $k => $v) {
                    if (is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }
                $route['controller'] = $this->upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }
    
    private function upperCamelCase(string $str)
    {
        return str_replace(" ", "", ucwords(str_replace("-", " ", $str)));
    }
    
    private function lowerCamelCase(string $str)
    {
        return lcfirst(self::upperCamelCase($str));
    }

    protected function removeQueryString($url)
    {
        if ($url) {
            $params = explode("&", $url, 2);
            if (strpos($params[0], "=") === false) {
                return rtrim($params[0], '/');
            }
        }
        return '';
    }
}