<?php

namespace Surfsail\interfaces;

interface ReviewModelInterface extends IContent
{
    public function getReviewsByProductId(int $product_id);
}