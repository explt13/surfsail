<?php
namespace app\models;

use app\models\interfaces\ArticleModelInterface;
use app\models\interfaces\FavoriteModelInterface;
use app\models\interfaces\ProductModelInterface;
use nosmi\App;

class FavoriteModel extends AppModel implements FavoriteModelInterface
{
    private ProductModelInterface $product_model;
    private ArticleModelInterface $article_model;

    public function __construct(ProductModelInterface $product_model, ArticleModelInterface $article_model)
    {
        parent::__construct();
        $this->product_model = $product_model;
        $this->article_model = $article_model;
        if (!isset($_SESSION['favorite'])) $_SESSION['favorite'] = [];
    }

    public function getItemsQty()
    {
        $bundle = $_SESSION['favorite'];
        if (App::$registry->getProperty('loggedIn') === false) {
            return 0;
        }
        return array_reduce($bundle, fn($a, $b) => $a + $b['qty'], 0);
    }

    public function getItemsIds()
    {
        return array_keys($_SESSION['favorite']);
    }

    public function deleteItem(int $product_id)
    {
        $bundle = &$_SESSION['favorite'];
        if (array_key_exists($product_id, $bundle)) {
            unset($bundle[$product_id]);
            return ["response_code" => 200, "message" => "Product has been removed"];
        } else {
            return ["response_code" => 400, "message" => "No such product in 'favorite'"];
        }
    }

    public function getItemsFromArray()
    {
        $array = $_SESSION['favorite'];
        if (!empty($array)) {
            $ids = array_keys($array);
            $result = $this->product_model->getProducts(["id" => $ids]);
    
            foreach ($result as &$res) {
                $product_id = $res['id'];
                $res['qty'] = $array[$product_id]['qty'];
                $res['added_date'] = $array[$product_id]['added_date'];
            }

            usort($result, fn($a, $b) => $b['added_date'] - $a['added_date']);
            return $result;
        }
        return false;
    }
    
    public function addItem(array $data)
    {
        $entity = $data['entity'];
        $product = $this->product_model->getProducts(['id' => $data['item_id']], 1);
        if ($product === false) {
            return ["response_code" => 409, 'message' => 'No such product'];
        }
        $favorite = &$_SESSION['favorite'][$entity];

        if (!array_key_exists($data['item_id'], $favorite)) {
            $favorite[$data['item_id']] = [
                "item_id" => $data['item_id'],
                "added_date" => time(),
            ];
            return ['response_code' => 200, 'message' => "$entity added successfully", 'action' => 'add'];
        } else {
            unset($favorite[$data['item_id']]);
            return ['response_code' => 200, 'message' => "$entity removed successfully", 'action' => 'remove'];
        }
    }
}