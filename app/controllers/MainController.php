<?php
namespace app\controllers;

use app\models\interfaces\BrandModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\models\interfaces\ArticleModelInterface;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use nosmi\App;

class MainController extends AppController
{
    protected $product_model;
    protected $brand_model;
    protected $article_model;

    public function __construct(
        ProductModelInterface $product_model,
        ArticleModelInterface $article_model,
        BrandModelInterface $brand_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($currency_model, $category_model);
        $this->product_model = $product_model;
        $this->brand_model = $brand_model;
        $this->article_model = $article_model;
    }

    public function indexAction()
    {
        $brands = $this->brand_model->getBrands(10);
        $shortboard_products = $this->product_model->getProductsBySubCategory('Shortboards', 7);
        $longboard_products = $this->product_model->getProductsBySubCategory('Longboards', 7);
        $new_products = $this->product_model->getProducts(['new' => 1], 10);
        $discount_products = $this->product_model->getProducts(['sale' => 1], 10, 0, 'discount_percentage', true);
        $recommend_products = $this->product_model->getProductsBySubCategory("Bags and Vests", 2);
        $gear_products = $this->product_model->getProducts(['category_alias' => 'protective-gear'], 6);
        $articles =  $this->article_model->getArticles(4);
        $categories = App::$registry->getProperty('categories');

        http_response_code(200);
        $this->setData(compact('brands', 'shortboard_products', 'new_products', 'longboard_products', 'discount_products', 'recommend_products', 'gear_products', 'articles', 'categories'));
        $this->setMeta(App::$registry->getProperty("app_name"), 'SurfSail - online shop in US', 'surfboards, wetsuits, online, shop');
        $this->getView();
    }
}