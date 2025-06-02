<?php

namespace Surfsail\services;

use Explt13\Nosmi\Interfaces\CacheFactoryInterface;
use Explt13\Nosmi\Interfaces\ConfigInterface;
use Surfsail\interfaces\CategoryModelInterface;

class CategoryService
{
    private CacheFactoryInterface $cache_factory;
    private CategoryModelInterface $category_model;
    private ConfigInterface $config;

    public function __construct(
        CacheFactoryInterface $cache_factory,
        CategoryModelInterface $category_model,
        ConfigInterface $config,
    )
    {
        $this->config = $config;
        $this->cache_factory = $cache_factory;
        $this->category_model = $category_model;
    }

    public function getCategories()
    {
        $cache = $this->cache_factory->createCacheBasedOnConfigHandler();
        $categories = $cache->get('categories');
        if (is_null($categories)) {
            $result = $this->category_model->getCategories();

            $sub_categories_to_show = $this->config->get('SUB_CATEGORIES_TO_SHOW');
            $categories = [
                "items" => [],
                "sub_categories_to_show" => $sub_categories_to_show,
            ];
            $sub_categories = [];

            foreach ($result as $row) {
                if (!isset($categories['items'][$row['c_id']])) {
                    $categories['items'][$row['c_id']] = [
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
                    
                    $categories['items'][$row['c_id']]['sub_categories'][] = &$sub_categories[$row['sc_id']];
                }
                
                $sub_categories[$row['sc_id']]['sub_categories'][] = [
                    'id' => $row['ssc_id'],
                    'title' => $row['ssc_title'],
                    'alias' => $row['ssc_alias'],
                    'sub_category_id' => $row['ssc_sub_category_id'],
                ];
            }
            $cache->set('categories', $categories, 3600 * 24 * 7 * 30);
        }
        return $categories;
    }
}