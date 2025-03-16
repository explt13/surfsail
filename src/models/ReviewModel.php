<?php
namespace Surfsail\models;

use Surfsail\models\interfaces\ReviewModelInterface;

class ReviewModel extends AppModel implements ReviewModelInterface
{
    public function getReviewsByProductId(int $product_id)
    {
        $stmt = $this->pdo->prepare("SELECT r.*, u.first_name, u.last_name, u.profile_pic
            FROM review r
            INNER JOIN user u ON r.user_id = u.id
            WHERE r.product_id = :product_id
        ");
        $stmt->execute(['product_id' => $product_id]);
        $reviews = $stmt->fetchAll();
    
        foreach ($reviews as &$review) {
            $review['user'] = array(
                "id" => $review["user_id"],
                "first_name" => $review["first_name"],
                "last_name" => $review["last_name"],
                "profile_pic" => $review["profile_pic"]
            );
            unset($review["user_id"], $review["first_name"], $review["last_name"], $review["profile_pic"]);
        }
        return $reviews;
    }

    public function publish()
    {
    }
    
    public function delete()
    {
    }
}