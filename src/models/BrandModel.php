<?php
namespace Surfsail\models;

use Surfsail\interfaces\BrandModelInterface;

class BrandModel extends AppModel implements BrandModelInterface
{
    public function getBrands(int $limit = 50): array
    {
        $sql = "SELECT b.* FROM brand b";

        $sql .= " LIMIT :lim";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getBrandById(int $brand_id): array
    {
        $stmt = $this->pdo->prepare('SELECT b.name, b.alias FROM brand b WHERE id = :id');
        $stmt->execute(['id' => $brand_id]);
        return $stmt->fetch();
    }
}