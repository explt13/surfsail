<?php
namespace nosmi;


class Router
{
    private array $routes;
    private array $route_params;
    private MiddlewareLoader $middleware_loader;
    private RouteContext $route_context;
    private ControllerResolver $controller_resolver;

    public function __construct(MiddlewareLoader $middleware_loader, RouteContext $route_context, ControllerResolver $controller_resolver, array $routes)
    {
        $this->middleware_loader = $middleware_loader;
        $this->route_context = $route_context;
        $this->controller_resolver = $controller_resolver;
        $this->routes = $routes;
    }

    public function add(string $regexp, array $route = []): self
    {
        $this->routes[$regexp] = $route;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
    
    public function dispatch(string $url): void
    {
        $route = $this->extractRouteFromQueryString($url);
        if ($this->routeExists($route)) {
            $this->route_context->setRoute($this->route_params);
            $this->middleware_loader->run();
            $this->controller_resolver->resolve();
        } else {
            throw new \Exception("Page not found", 404);
        }
    }

    private function routeExists(string $route): bool
    {
        foreach ($this->routes as $pattern => $default_params) {
            if (preg_match("#{$pattern}#", $route, $matches)) {
                $matches = array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $this->route_params = [...$default_params, ...$matches];
                return true;
            }
        }
        return false;
    }
    

    protected function extractRouteFromQueryString(string $url): string
    {
        if ($url) {
            list($route, $query) = explode("&", $url, 2);
            if (strpos($route, "=") === false) {
                return rtrim($route, '/');
            }
        }
        return '';
    }
}