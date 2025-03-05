<?php
namespace app\models;

use app\models\interfaces\CategoryModelInterface;
use nosmi\App;
use nosmi\Cache;
use nosmi\interfaces\CacheInterface;

class CategoryModel extends AppModel implements CategoryModelInterface
{
    protected CacheInterface $cache;
    public function __construct(CacheInterface $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }
    public function getCategories()
    {
        $categories = $this->cache->get('categories');
        if (!$categories) {
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
            INNER JOIN category c ON sc.category_id = c.id
            ");
            $result = $stmt->fetchAll();

            $categories = [];
            $sub_categories = [];
            
            foreach ($result as $row) {
                if (!isset($categories[$row['c_id']])) {
                    $categories[$row['c_id']] = [
                        'id' => $row['c_id'],
                        'title' => $row['c_title'],
                        'alias' => $row['c_alias'],
                        'image' => $row['c_image'],
                        'meta_keywords' => $row['c_meta_keywords'],
                        'meta_desc' => $row['c_meta_desc'],
                        'sub_categories' => [],
                    ];
                }
                
                if (!isset($sub_categories[$row['sc_id']])) {
                    $sub_categories[$row['sc_id']] = [
                        'id' => $row['sc_id'],
                        'title' => $row['sc_title'],
                        'alias' => $row['sc_alias'],
                        'category_id' => $row['sc_category_id'],
                        'sub_categories_count' => $row['sc_sub_categories_count'],
                        'sub_categories' => [],
                    ];
                    
                    $categories[$row['c_id']]['sub_categories'][] = &$sub_categories[$row['sc_id']];
                }
                
                $sub_categories[$row['sc_id']]['sub_categories'][] = [
                    'id' => $row['ssc_id'],
                    'title' => $row['ssc_title'],
                    'alias' => $row['ssc_alias'],
                    'sub_category_id' => $row['ssc_sub_category_id'],
                ];
            }
            $this->cache->set('categories', $categories, 3600 * 24 * 7 * 30);
        }
        return $categories;
    }
}