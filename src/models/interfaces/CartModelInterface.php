<?php

namespace Surfsail\models\interfaces;

interface CartModelInterface
{
    public function getAddedProductsIds();
    public function getProductsFromArray();
    public function deleteProduct(int $product_id);
    public function getProductsQty();
    public function addProduct(array $data);
    public function addMultipleProducts(array $data);
}