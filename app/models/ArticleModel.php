<?php
namespace app\models;

class ArticleModel extends AppModel
{
    public function getArticles($limit)
    {
        $sql = "SELECT a.* FROM article a";

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
}