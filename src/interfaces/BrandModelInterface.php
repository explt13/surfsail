<?php

namespace Surfsail\interfaces;

interface BrandModelInterface
{
    public function getBrands(int $limit = 50): array;
    public function getBrandById(int $brand_id);
}