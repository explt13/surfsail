<?php
namespace app\models;

class ProductModel extends AppModel
{
    public function getProductByAlias(string $alias)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product p WHERE p.alias = :alias AND p.active = 1');
        $stmt->execute(['alias' => $alias]);
        $result = $stmt->fetch();
        return $result;
    }

    public function getProductsByIds(array $ids)
    {
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->prepare("SELECT p.* FROM product p WHERE p.id IN ($placeholders)");
            $stmt->execute(array_keys($ids));
            $result = $stmt->fetchAll();
            $products = [];
            foreach ($result as $product) {
                $products[$product["id"]] = $product;
            }
            foreach ($ids as $id => $qty) {
                $products[$id]['qty'] = $qty;
            }
            return $products;
        }
        return false;
    }
    
    public function getProductGalleryImages(int $product_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product_gallery WHERE product_id = :id');
        $stmt->execute(['id' => $product_id]);
        return $stmt->fetchAll();
    }

    public function getRelatedProducts(int $product_id)
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM product_related pr INNER JOIN product p ON pr.related_id = p.id WHERE pr.product_id = :id');
        $stmt->execute(['id' => $product_id]);
        return $stmt->fetchAll();
    }

    public function getProductMods(int $product_id)
    {
        $stmt = $this->pdo->prepare("SELECT
        mt.name AS modifier_name,
        mt.value AS modifier_value,
        mo.name AS option_name,
        mo.value AS option_value,
        mo.modifier_type_id AS modifier_type_id
        FROM product_modifier pm
        INNER JOIN modifier_option mo ON pm.modifier_option_id = mo.id
        INNER JOIN modifier_type mt ON mo.modifier_type_id = mt.id
        WHERE pm.product_id = :id");

        $stmt->execute(['id' => $product_id]);
        $mods = $stmt->fetchAll();

        $result = [];

        if ($mods) {
            foreach ($mods as $mod) {
                if (!isset($result[$mod['modifier_name']])) {
                    $result[$mod['modifier_name']] = [
                        'name' => $mod['modifier_name'],
                        'value' => $mod['modifier_value'],
                        'options' => []
                    ];
                }

                $result[$mod['modifier_name']]['options'][] = [
                    'name' => $mod['option_name'],
                    'value' => $mod['option_value']
                ];
            }
        }
        return $result;
    }

    public function getProductsBySubCategory(string $sub_categroy, int $limit)
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM product p INNER JOIN sub_sub_category ssc ON p.ssc_id = ssc.id
        WHERE ssc.sub_category_id = (SELECT sc.id FROM sub_category sc WHERE sc.title = :sub_category) LIMIT :lim');
        $stmt->execute(['sub_category' => $sub_categroy, 'lim' => $limit]);
        return $stmt->fetchAll();
    }
    
    public function getNewProducts(int $limit)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product p WHERE p.new = 1 LIMIT :lim');
        $stmt->execute(['lim' => $limit]);
        return $stmt->fetchAll();
    }

    public function getDiscountProducts(int $limit)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product p WHERE p.sale = 1 ORDER BY p.discount_percentage DESC LIMIT :lim');
        $stmt->execute(['lim' => $limit]);
        return $stmt->fetchAll();
    }
    public function getProductsByCategoryAlias(string $category_alias, int $limit)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product p WHERE p.category_alias = :category_alias LIMIT :lim');
        $stmt->execute(['category_alias' => $category_alias, 'lim' => $limit]);
        return $stmt->fetchAll();
    }
}