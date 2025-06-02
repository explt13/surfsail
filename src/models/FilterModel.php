<?php

namespace Surfsail\models;

use Explt13\Nosmi\Interfaces\CacheFactoryInterface;
use Explt13\Nosmi\Interfaces\CacheInterface;
use Surfsail\interfaces\FilterModelInterface;

class FilterModel extends AppModel implements FilterModelInterface
{
    protected CacheFactoryInterface $cache_factory;
    protected array $filters = [];

    public function __construct(CacheFactoryInterface $cache_factory)
    {
        parent::__construct();
        $this->cache_factory = $cache_factory;
    }
    public function getFilters()
    {
        $groups = $this->getGroups();
        $options = $this->getAllOptions();

        $groupedOptions = [];
        foreach ($options as $option) {
            $groupedOptions[$option['filter_group_id']][] = $option;
        }

        foreach ($groups as &$group) {
            $group['options'] = $groupedOptions[$group['id']] ?? [];
        }
        return $groups;
    }

    protected function getGroups()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM filter_group ORDER BY `order` ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    protected function getAllOptions()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM filter_option');
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
