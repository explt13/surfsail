<?php

namespace Surfsail\interfaces;

interface CartModelInterface
{
    public function getCartCount(): int;
    public function getProductsQty(): int;
    public function getAddedProductsIds(): array;
    public function getProductsFromArray(): array;
    public function deleteProduct(int $product_id);
    public function addProduct(array $data);
}