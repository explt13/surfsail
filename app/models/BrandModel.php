<?php
namespace app\models;

class BrandModel extends AppModel
{
    public function getBrands(?int $limit = null)
    {
        $sql = "SELECT b.* FROM brand b";

        if ($limit !== null) {
            $sql .= " LIMIT :lim";
        }
        $stmt = $this->pdo->prepare($sql);

        if ($limit !== null) {
            $stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
        }
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    public function getProductBrand(?int $brand_id)
    {
        if ($brand_id) {
            $stmt = $this->pdo->prepare('SELECT title, alias FROM brand WHERE id = :id');
            $stmt->execute(['id' => $brand_id]);
            return $stmt->fetch();
        }
    }
}