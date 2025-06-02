<?php

namespace Surfsail\interfaces;

interface FavoriteModelInterface
{
    public function getItemsIds(string $entity);
    public function getItemsFromArray(string $entity);
    public function deleteItem(int $item_id, string $entity);
    public function addItem(string $entity, int $item_id);
}