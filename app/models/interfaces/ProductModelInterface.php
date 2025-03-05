<?php

namespace app\models\interfaces;

interface ProductModelInterface
{
    /**
     * fetch mode using `AND` operator, WHERE a=1 AND b=2
     */
    public const STRICT_FETCH_MODE = 1;
    
    /**
     * fetch mode using `OR` operator, WHERE a=1 OR b=2
     */
    public const SOFT_FETCH_MODE = 2;

    /**
     * @param int $fetch_mode => operator used in fetching STRICT_FETCH_MODE => AND, SOFT_FETCH_MODE => OR. STRICT_FETCH_MODE is used by default
     */
    public function getProducts(array $filters = [], int $limit = 100, int $offset = 0, string $orderBy = null, bool $desc = false, int $fetch_mode = self::STRICT_FETCH_MODE): array;
    public function getProductGalleryImages(int $product_id);
    public function getRelatedProducts(int $product_id, int $limit = 50);
    public function getProductMods(int $product_id);
    public function getProductsBySubCategory(string $sub_category, int $limit = 100);
    
    /**
     * @return array "count" => int, "products" => array[][];
     */
    public function getFilteredProducts(array $filters, int $limit = 100, int $offset = 0, string $orderBy = null, bool $desc = false): array;
    
    
    public function getProductsCount();
}