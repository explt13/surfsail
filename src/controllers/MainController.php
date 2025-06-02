<?php
namespace Surfsail\controllers;

use Surfsail\interfaces\BrandModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Surfsail\interfaces\ArticleModelInterface;
use Explt13\Nosmi\AppConfig\AppConfig;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;

class MainController extends BaseController
{
    protected $product_model;
    protected $brand_model;
    protected $article_model;
    protected CurrencyService $currency_service;
    protected CategoryService $category_service;

    public function __construct(
        ProductModelInterface $product_model,
        ArticleModelInterface $article_model,
        BrandModelInterface $brand_model,
        CurrencyService $currency_service,
        CategoryService $category_service,
    )
    {
        parent::__construct();
        $this->currency_service = $currency_service;
        $this->product_model = $product_model;
        $this->brand_model = $brand_model;
        $this->article_model = $article_model;
        $this->category_service = $category_service;
    }

    public function indexAction()
    {
        $brands = $this->brand_model->getBrands(10);
        $currency = $this->currency_service->getCurrencyByCookie();
        $shortboard_products = $this->product_model->getProductsBySubCategory('Shortboards', 7);
        $longboard_products = $this->product_model->getProductsBySubCategory('Longboards', 7);
        $new_products = $this->product_model->getProducts(['new' => 1], 10);
        $discount_products = $this->product_model->getProducts(['sale' => 1], 10, 0, 'discount_percentage', true);
        $recommend_products = $this->product_model->getProductsBySubCategory("Bags and Vests", 2);
        $gear_products = $this->product_model->getProducts(['category_alias' => 'protective-gear'], 6);
        $articles =  $this->article_model->getArticles(4);
        $categories = $this->category_service->getCategories();
        $config = AppConfig::getInstance();
        $render = $this->getView()
             ->withMetaArray([
                'title' => $config->get("APP_NAME"), 
                'description' => 'SurfSail - online shop in US',
                'keywords' => 'surfboards, wetsuits, online, shop'
            ])
            ->render('index', compact('brands', 'shortboard_products', 'new_products', 'longboard_products', 'discount_products', 'recommend_products', 'gear_products', 'articles', 'categories', 'currency'));
        $this->response = $this->response->withStatus(200);
        $this->response->getBody()->write($render);
    }
}