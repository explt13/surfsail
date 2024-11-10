<?php
namespace app\models;

class FavoriteModel extends BundleModel
{
    protected string $name = 'favorite';
    public function addProduct(bool|array $product, array $data)
    {
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