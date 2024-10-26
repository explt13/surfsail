<?php
namespace app\models;

class ProductModel extends AppModel
{
    
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
    
    public function getProducts(array $filters = [], int $limit = null, int $offset = 0, string $orderBy = null, $desc = false)
    {
        $query = 'SELECT * FROM product p';
        $allowedOrderColumns = ['price', 'title', 'discount_percentage', 'added_at', 'id'];
        $params = [];

        if (!empty($filters)) {
            $query .= " WHERE 1=1";
            foreach ($filters as $fname => $fvalue) {
                if (is_array($fvalue)) {
                    $placeholders = implode(',', array_fill(0, count($fvalue), '?'));
                    $query .= " AND p.$fname IN ($placeholders)";
                    $params = array_merge($params, $fvalue);
                } elseif (strpos($fname, 'LIKE_') === 0) {
                    // Handle LIKE cases (e.g. 'LIKE_title')
                    $columnName = substr($fname, 5);
                    $query .= " AND p.$columnName LIKE :$fname";
                    $params[$fname] = "%$fvalue%";
                } else {
                    $query .= " AND p.$fname = :$fname";
                    $params[$fname] = $fvalue;
                }

            }
        }
        if ($orderBy && in_array($orderBy, $allowedOrderColumns)) {
            $query .= ' ORDER BY p.' . $orderBy . ($desc ? ' DESC' : ' ASC');
        }

        if ($limit) {
            $query .= ' LIMIT :lim';
            $params['lim'] = $limit;
        }
        if ($offset) {
            $query .= ' OFFSET :ofs';
            $params['ofs'] = $offset;
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return ($limit === 1) ? $stmt->fetch() : $stmt->fetchAll();
    }
    public function getTotalProducts(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM product");
        return (int) $stmt->fetchColumn();
    }
    public function getProductsFromArray(array $array)
    {
        if (!empty($array)) {
            $product_model = new ProductModel();
            $ids = array_keys($array);
            $result = $product_model->getProducts(["id" => $ids]);
    
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

}