<?php

namespace app\models;

use app\models\interfaces\FilterModelInterface;
use nosmi\interfaces\CacheInterface;

class FilterModel extends AppModel implements FilterModelInterface
{
    protected CacheInterface $cache;
    protected array $filters = [];

    public function __construct(CacheInterface $cache)
    {
        parent::__construct();
        $this->cache = $cache;
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
