<?php

namespace app\models\interfaces;

interface BundleModelInterface
{
    public function getProductsIds();
    public function getProductsFromArray();
    public function deleteProduct(int $product_id);
    public function addProduct(array $data);
    public function getItemsQty();
}