<?php
namespace nosmi;

class Router
{
    private static array $routes = [];
    private static array $route = [];

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
    
    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if (self::routeExists($url)) {
            $controller = 'app\\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';
            $controllerReflection = new \ReflectionClass($controller);
            if (class_exists($controller) && !$controllerReflection->isAbstract()) {
                $container = Container::getInstance();

                $controllerObject = $container->get($controller);

                $action = self::lowerCamelCase(self::$route['action']) . "Action";
                if (method_exists($controllerObject, $action)) {
                    $controllerObject->$action();
                } else {
                    throw new \Exception("Action: $controller::$action not found", 404);
                }

            } else {
                throw new \Exception("Controller: $controller not found", 404);
            }
            
        } else {
            throw new \Exception("Page not found", 404);
        }
    }
    private static function routeExists($url)
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
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }
    
    private static function upperCamelCase(string $str)
    {
        return str_replace(" ", "", ucwords(str_replace("-", " ", $str)));
    }
    
    private static function lowerCamelCase(string $str)
    {
        return lcfirst(self::upperCamelCase($str));
    }

    protected static function removeQueryString($url)
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