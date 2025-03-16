<?php

namespace Surfsail\models\interfaces;

interface FavoriteModelInterface
{
    public function getItemsIds(string $entity);
    public function getItemsFromArray(string $entity);
    public function deleteItem(array $data, string $entity);
    public function getItemsQty(string $entity);
    public function addItem(array $data);
}