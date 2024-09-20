<?php
namespace app\controllers;

use app\models\ArticleModel;
use app\models\BrandModel;
use app\models\ProductModel;
use nosmi\App;

class MainController extends AppController
{
    public function indexAction()
    {
        $brands_model = new BrandModel();
        $product_model = new ProductModel();
        $article_model = new ArticleModel();

        $brands = $brands_model->getBrands(10);
        $shortboard_products = $product_model->getProductsBySubCategory('Shortboards', 7);
        $longboard_products = $product_model->getProductsBySubCategory('Longboards', 7);
        $new_products = $product_model->getNewProducts(10);
        $discount_products = $product_model->getDiscountProducts(10);
        $recommend_products = $product_model->getProductsBySubCategory("Bags and Vests", 2);
        $gear_products = $product_model->getProductsByCategoryAlias('protective-gear', 6);
        $articles =  $article_model->getArticles(4);
        $categories = App::$registry->getProperty('categories');
  
        $this->setData(compact('brands', 'shortboard_products', 'new_products', 'longboard_products', 'discount_products', 'recommend_products', 'gear_products', 'articles', 'categories'));
        $this->setMeta(App::$registry->getProperty("app_name"), 'SurfSail - online shop in US', 'surfboards, wetsuits, online, shop');
        $this->getView();
    }
}