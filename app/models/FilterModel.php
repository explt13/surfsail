<?php

namespace app\models;

use app\models\interfaces\FilterModelInterface;
use nosmi\CacheInterface;

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
        $filters = [];

        foreach ($this->getGroups() as $group) {
            $group['options'] = $this->getOptions($group["id"]);
            $filters[] = $group;
        }

        return $filters;
    }

    protected function getGroups()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM filter_group ORDER BY `order` ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    protected function getOptions(int $group_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM filter_option WHERE filter_group_id=:group_id');
        $stmt->bindValue(':group_id', $group_id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
