<?php

namespace app\models\interfaces;

interface ProductModelInterface
{
    public function getProducts(array $filters = [], int $limit = null, int $offset = 0, string $orderBy = null, $desc = false);
    public function getTotalProducts(): int;
    public function getProductGalleryImages(int $product_id);
    public function getRelatedProducts(int $product_id);
    public function getProductMods(int $product_id);
    public function getProductsBySubCategory(string $sub_categroy, int $limit);
}