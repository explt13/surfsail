<?php
namespace nosmi;

class App
{
    public static Registry $registry;
    private ContainerInterface $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    public function bootstrap()
    {
        session_start();
        ErrorHandler::getInstance();
        cors();
        $container_dependencies = require_once CONF . '/dependencies.php';
        $this->container->init($container_dependencies);
        self::$registry = Registry::getInstance();
        self::$registry->setParams(require_once CONF . '/params.php');
        $serviceLoader = $this->container->get(ServiceProviderLoader::class);
        $serviceLoader->load();
    }

    public function run()
    {
        $this->container->set(Router::class, fn (ContainerInterface $container) =>
            new Router(
                $container->get(MiddlewareLoader::class),
                $container->get(RouteContext::class),
                $container->get(ControllerResolver::class),
                require_once CONF . '/routes.php'
            )
        );
        $router = $this->container->get(Router::class);
        $router->dispatch($_SERVER['QUERY_STRING']);
    }
}
