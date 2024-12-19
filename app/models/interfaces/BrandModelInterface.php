<?php

namespace app\models\interfaces;

interface BrandModelInterface
{
    public function getBrands(?int $limit = null);
    public function getBrandById(int $brand_id);
}