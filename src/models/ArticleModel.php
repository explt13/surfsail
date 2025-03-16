<?php
namespace Surfsail\models;

use Surfsail\models\interfaces\ArticleModelInterface;

class ArticleModel extends AppModel implements ArticleModelInterface
{
    public function getArticles(int $limit = 50)
    {
        $sql = "SELECT a.* FROM article a";
        $sql .= " LIMIT :lim";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
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