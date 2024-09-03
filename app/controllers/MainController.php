<?php
namespace app\controllers;

use nosmi\Cache;
use nosmi\App;

class MainController extends AppController
{
    public function indexAction()
    {
        $brands = \R::getAll("SELECT b.* FROM brand b LIMIT 6");
        $shortboard_products = \R::getAll('SELECT p.* FROM product p INNER JOIN sub_sub_category ssc ON p.ssc_id = ssc.id
        WHERE ssc.sub_category_id = (SELECT sc.id FROM sub_category sc WHERE sc.title = \'Shortboards\') LIMIT 7');
        $longboard_products = \R::getAll('SELECT p.* FROM product p INNER JOIN sub_sub_category ssc ON p.ssc_id = ssc.id
        WHERE ssc.sub_category_id = (SELECT sc.id FROM sub_category sc WHERE sc.title = \'Longboards\') LIMIT 7');
        $new_products = \R::getAll('SELECT * FROM product p WHERE p.new = 1 LIMIT 10');
        $discount_products = \R::getAll('SELECT * FROM product p WHERE p.sale = 1 ORDER BY p.discount_percentage DESC LIMIT 10');
        $recommend_products = \R::getAll('SELECT p.* FROM product p INNER JOIN sub_sub_category ssc ON p.ssc_id = ssc.id
        WHERE ssc.sub_category_id = (SELECT sc.id FROM sub_category sc WHERE sc.title = \'Bags and Vests\') LIMIT 2');
        $gear_products = \R::getAll('SELECT * FROM product p WHERE p.category_alias = \'protective-gear\' LIMIT 6');
        $articles =  \R::getAll('SELECT * FROM article LIMIT 4');
        $categories = App::$registry->getProperty('categories');
  
        $this->setData(compact('brands', 'shortboard_products', 'new_products', 'longboard_products', 'discount_products', 'recommend_products', 'gear_products', 'articles', 'categories'));
        $this->setMeta(App::$registry->getProperty("app_name"), 'SurfSail - online shop in US', 'surfboards, wetsuits, online, shop');
        $this->getView();
    }
}