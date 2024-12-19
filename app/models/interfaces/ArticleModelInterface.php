<?php

namespace app\models\interfaces;

interface ArticleModelInterface extends IContent
{
    public function getArticles(?int $limit);
}