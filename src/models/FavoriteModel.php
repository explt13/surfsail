<?php
namespace Surfsail\models;

use Surfsail\interfaces\ArticleModelInterface;
use Surfsail\interfaces\FavoriteModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Explt13\Nosmi\App;

class FavoriteModel extends AppModel implements FavoriteModelInterface
{
    private ProductModelInterface $product_model;
    private ArticleModelInterface $article_model;

    public function __construct(ProductModelInterface $product_model, ArticleModelInterface $article_model)
    {
        parent::__construct();
        $this->product_model = $product_model;
        $this->article_model = $article_model;
    }

    public function getItemsIds(string $entity): array
    {
        return array_keys($_SESSION['favorite'][$entity]);
    }

    public function deleteItem(int $item_id, string $entity)
    {
        $bundle = &$_SESSION['favorite'][$entity];
        if (array_key_exists($item_id, $bundle)) {
            unset($bundle[$item_id]);
            return ["response_code" => 200, "message" => "$entity has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such $entity in 'favorite'"];
        }
    }

    public function getItemsFromArray(string $entity)
    {
        $array = $_SESSION['favorite'][$entity];
        if (!empty($array)) {
            $ids = array_keys($array);
            $result = $this->product_model->getProducts(["id" => $ids]);
    
            foreach ($result as &$res) {
                $product_id = $res['id'];
                $res['added_date'] = $array[$product_id]['added_date'];
            }

            usort($result, fn($a, $b) => $b['added_date'] - $a['added_date']);
            return $result;
        }
        return false;
    }
    
    public function addItem(string $entity, int $item_id)
    {
        $product = $this->product_model->getProducts(['id' => $item_id], 1);
        if ($product === false) {
            return ["response_code" => 409, 'message' => 'No such product'];
        }
        
        $favorite = &$_SESSION['favorite'][$entity];

        if (!array_key_exists($item_id, $favorite)) {
            $favorite[$item_id] = [
                "item_id" => $item_id,
                "added_date" => time(),
            ];
            return ['response_code' => 200, 'message' => "$entity added successfully", 'action' => 'add'];
        } else {
            unset($favorite[$item_id]);
            return ['response_code' => 200, 'message' => "$entity removed successfully", 'action' => 'remove'];
        }
    }
}