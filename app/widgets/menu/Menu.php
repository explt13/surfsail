<?php

namespace app\widgets\menu;

use app\models\CategoryModel;
use nosmi\base\Widget;
use nosmi\App;
use nosmi\Cache;
use nosmi\interfaces\CacheInterface;

class Menu extends Widget
{
    protected string $prepend = '';
    protected ?array $categories = [];
    protected string $cache_key = 'menu';
    protected int $cache_time = 3600 * 24 * 30;
    protected CacheInterface $cache;
    protected int $sub_categories_to_show;
    protected ?string $menuHtml;

    public function __construct($props = [])
    {
        parent::__construct(null);
        $this->cache = Cache::getInstance();
        $this->sub_categories_to_show = App::$registry->getProperty('sub_categories_to_show');
        $this->setProps($props);
        $this->cacheMenu();
    }
    
    private function setProps($props)
    {
        foreach($props as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    private function cacheMenu()
    {
        $this->menuHtml = $this->cache->get($this->cache_key);
        if (!$this->menuHtml) {
            $this->categories = App::$registry->getProperty('categories');
            $this->menuHtml = $this->getMenuHtml();
            $this->cache->set($this->cache_key, $this->menuHtml, $this->cache_time);
        }
    }

    private function getMenuHtml()
    {
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }

    public function render()
    {
        echo $this->menuHtml;
    }
}