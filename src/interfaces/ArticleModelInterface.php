<?php

namespace Surfsail\interfaces;

interface ArticleModelInterface extends IContent
{
    public function getArticles(int $limit = 50);
}