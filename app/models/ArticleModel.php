<?php
namespace app\models;

use app\models\interfaces\ArticleModelInterface;

class ArticleModel extends AppModel implements ArticleModelInterface
{
    public function getArticles(?int $limit)
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
    public function publish()
    {
    }
    
    public function delete()
    {
    }
}