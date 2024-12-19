<?php

namespace app\controllers;

use app\models\interfaces\BrandModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\models\interfaces\ReviewModelInterface;
use nosmi\App;

class ProductController extends AppController
{
    protected $product_model;
    protected $brand_model;
    protected $review_model;

    public function __construct(ProductModelInterface $product_model, ReviewModelInterface $review_model, BrandModelInterface $brand_model)
    {
        $this->product_model = $product_model;
        $this->brand_model = $brand_model;
        $this->review_model = $review_model;
    }
    public function viewAction()
    {
        $currency = App::$registry->getProperty('currency');
        $alias = $this->route->alias;


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

        http_response_code(200);
        $this->setData(compact('product', 'currency', 'gallery_images', 'product_brand', 'related_products', 'reviews', 'mods'));
        $this->setMeta($product['title'], $product['meta_desc'], $product['meta_kwords']);
        $this->getView();
    }
}