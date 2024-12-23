<?php
namespace app\models;

use app\models\interfaces\FavoriteModelInterface;
use app\models\interfaces\ProductModelInterface;

class FavoriteModel extends BundleModel implements FavoriteModelInterface
{
    protected string $name = 'favorite';
    public function __construct(ProductModelInterface $product_model)
    {
        parent::__construct($product_model);
    }
    
    public function addProduct(array $data)
    {
        $product = $this->product_model->getProducts(['id' => $data['product_id']], 1);
        if ($product === false) {
            return ["response_code" => 409, 'message' => 'No such product'];
        }
        $favorite = &$_SESSION['favorite'];

        if (!array_key_exists($data['product_id'], $favorite)) {
            $favorite[$data['product_id']] = [
                "product_id" => $data['product_id'],
                "added_date" => time(),
            ];
            return ['response_code' => 200, 'message' => 'Item added successfully', 'action' => 'add'];
        } else {
            unset($favorite[$data['product_id']]);
            return ['response_code' => 200, 'message' => 'Item removed successfully', 'action' => 'remove'];
        }
    }
}