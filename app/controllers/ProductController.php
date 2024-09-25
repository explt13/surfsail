<?php

namespace app\controllers;

use app\models\BrandModel;
use app\models\ProductModel;
use app\models\ReviewModel;
use nosmi\App;

class ProductController extends AppController
{
    public function viewAction()
    {
        $currency = App::$registry->getProperty('currency');
        $alias = $this->route['alias'];

        $product_model = new ProductModel();
        $brand_model = new BrandModel();
        $review_model = new ReviewModel();

        $product = $product_model->getProductByAlias($alias);
        if (!$product) {
            throw new \Exception('Product not found', 404);
        }

        $product_brand = $brand_model->getProductBrand($product['brand_id']);
        $gallery_images = $product_model->getProductGalleryImages($product['id']);
        $reviews = $review_model->getReviewsByProductId($product['id']);
        $related_products = $product_model->getRelatedProducts($product['id']);
        $mods = $product_model->getProductMods($product['id']);

        http_response_code(200);
        $this->setData(compact('product', 'currency', 'gallery_images', 'product_brand', 'related_products', 'reviews', 'mods'));
        $this->setMeta($product['title'], $product['meta_desc'], $product['meta_kwords']);
        $this->getView();
    }
}