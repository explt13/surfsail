<?php

namespace app\controllers;

use nosmi\App;

class ProductController extends AppController
{
    public function viewAction()
    {
        $alias = $this->route['alias'];
        $product = \R::getRow('SELECT * FROM product p WHERE p.alias = ? AND p.active = 1', [$alias]);
        $currency = App::$registry->getProperty('currency');
        if (!$product) {
            throw new \Exception('Product not found', 404);
        }
        $product_brand = \R::getRow('SELECT title, alias FROM brand WHERE id = ?', [$product['brand_id']]);
        $gallery_images = \R::getAll("SELECT * FROM product_gallery WHERE product_id = ?", [$product['id']]);
        
        $reviews = \R::getAll("SELECT r.*, u.first_name, u.last_name, u.profile_pic FROM review r INNER JOIN user u ON r.user_id = u.id WHERE r.product_id = ?", [$product['id']]);
        if ($reviews) {
            foreach ($reviews as &$review) {
                $review['user'] = array(
                    "id" => $review["user_id"],
                    "first_name" => $review["first_name"],
                    "last_name" => $review["last_name"],
                    "profile_pic" => $review["profile_pic"]
                );
                unset($review["user_id"], $review["first_name"], $review["last_name"], $review["profile_pic"]);
            }
        }

        $related_products = \R::getAll("SELECT p.* FROM product_related pr INNER JOIN product p ON pr.related_id = p.id WHERE pr.product_id = ?", [$product['id']]);

        $this->setData(compact('product', 'currency', 'gallery_images', 'product_brand', 'related_products', 'reviews'));
        $this->setMeta($product['title'], $product['meta_desc'], $product['meta_kwords']);
        $this->getView();
    }
}