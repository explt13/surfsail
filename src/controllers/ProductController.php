<?php

namespace Surfsail\controllers;

use Surfsail\interfaces\BrandModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Surfsail\interfaces\ReviewModelInterface;
use Surfsail\services\CurrencyService;

class ProductController extends BaseController
{
    protected $product_model;
    protected $brand_model;
    protected $review_model;
    protected CurrencyService $currency_service;

    public function __construct(
        ProductModelInterface $product_model,
        ReviewModelInterface $review_model,
        BrandModelInterface $brand_model,
        CurrencyService $currency_service,
    )
    {
        parent::__construct();
        $this->product_model = $product_model;
        $this->brand_model = $brand_model;
        $this->review_model = $review_model;
        $this->currency_service = $currency_service;
    }
    
    public function indexAction()
    {
        $currency = $this->currency_service->getCurrencyByCookie();
        $alias = $this->getRoute()->getParam('alias');

        $product = $this->product_model->getProducts(['alias' => $alias, 'active' => 1], 1);
        if (!$product) {
            throw new \Exception('Product not found', 404);
        }
        if ($product['brand_id']) {
            $product_brand = $this->brand_model->getBrandById($product['brand_id']);
        }
        $gallery_images = $this->product_model->getProductGalleryImages($product['id']);
        $reviews = $this->review_model->getReviewsByProductId($product['id']);
        $related_products = $this->product_model->getRelatedProducts($product['id']);
        $mods = $this->product_model->getProductMods($product['id']);

        $html = $this->getView()->withMetaArray([
            'title' => $product["name"],
            'description' => $product['meta_desc'],
            'keywords' => $product['meta_kwords']
        ])->render('index', compact('product', 'currency', 'gallery_images', 'product_brand', 'related_products', 'reviews', 'mods'));
        $this->response = $this->response->withStatus(200)->withHtml($html);
    }
}