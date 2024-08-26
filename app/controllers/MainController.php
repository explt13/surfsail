<?php
namespace app\controllers;

use nosmi\Cache;

class MainController extends AppController
{
    public function indexAction()
    {
        $subcategories_to_show = \nosmi\App::$registry->getProperty('subcategories_to_show');
        $categories = $this->getCategories();
        $brands = \R::find("brand", "LIMIT 6");
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
        $this->setData(compact('brands', 'categories', 'subcategories_to_show', 'shortboard_products', 'new_products', 'longboard_products', 'discount_products', 'recommend_products', 'gear_products', 'articles'));
        $this->setMeta(\nosmi\App::$registry->getProperty("app_name"), 'SurfSail - online shop in US', 'surfboards, wetsuits, online, shop');
        $this->getView();
    }

    private function getCategories()
    {
        $subcategories_to_show = \nosmi\App::$registry->getProperty('subcategories_to_show');
        $categories = \R::getAll('SELECT * FROM category');
        foreach ($categories as &$category) {
            $category["sub_categories"] = \R::getAll("SELECT * FROM sub_category WHERE category_id = ? LIMIT 5", [$category["id"]]);
            foreach ($category["sub_categories"] as &$sub_category) {
                $sub_category["sub_categories"]= \R::getAll("SELECT * FROM sub_sub_category WHERE sub_category_id = ? LIMIT ?", [$sub_category["id"], $subcategories_to_show]);
            }
        }
        return $categories;
    }
}