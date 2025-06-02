<?php
namespace Surfsail\models;

use Surfsail\interfaces\CategoryModelInterface;
use Explt13\Nosmi\App;
use Explt13\Nosmi\Cache;
use Explt13\Nosmi\interfaces\CacheInterface;

class CategoryModel extends AppModel implements CategoryModelInterface
{
    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT
            ssc.id as ssc_id,
            ssc.title as ssc_title,
            ssc.alias as ssc_alias,
            ssc.sub_category_id as ssc_sub_category_id,
            sc.id as sc_id,
            sc.title as sc_title,
            sc.alias as sc_alias,
            sc.category_id as sc_category_id,
            sc.sub_categories_count as sc_sub_categories_count,
            c.id as c_id,
            c.title as c_title,
            c.alias as c_alias,
            c.image as c_image,
            c.meta_keywords as c_meta_keywords,
            c.meta_desc as c_meta_desc
            FROM sub_sub_category ssc
            INNER JOIN sub_category sc ON ssc.sub_category_id = sc.id
            INNER JOIN category c ON sc.category_id = c.id"
        );
        return $stmt->fetchAll();
    }
}