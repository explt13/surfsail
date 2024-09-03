<?php

namespace app\widgets\menu;

use nosmi\App;
use nosmi\Cache;

class Menu
{
    private string $tpl;
    private string $prepend = '';
    private ?array $categories = [];
    private string $cache_key = 'menu';
    private int $cache_time = 3600 * 24 * 30;
    private int $sub_categories_to_show;
    private ?string $menuHtml;

    public function __construct($props = [])
    {
        $this->sub_categories_to_show = App::$registry->getProperty('sub_categories_to_show');
        $this->tpl = __DIR__.'/tpl/menu_tpl.php';
        $this->setProps($props);
        $this->run();
    }
    
    private function setProps($props)
    {
        foreach($props as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    private function run()
    {
        $cache = Cache::getInstance();
        $this->menuHtml = $cache->get($this->cache_key);
        if (!$this->menuHtml) {
            $this->categories = App::$registry->getProperty('categories');
            $this->menuHtml = $this->getMenuHtml();
            $cache->set($this->cache_key, $this->menuHtml, $this->cache_time);
        }
        echo $this->menuHtml;
    }

    private function getMenuHtml()
    {
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }    
}