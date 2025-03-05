<?php

namespace app\models\interfaces;

interface FavoriteModelInterface
{
    public function getItemsIds();
    public function getItemsFromArray();
    public function deleteItem(int $product_id);
    public function getItemsQty();
    public function addItem(array $data);
}