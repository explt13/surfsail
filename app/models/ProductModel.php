<?php
namespace app\models;

use app\models\interfaces\ProductModelInterface;

class ProductModel extends AppModel implements ProductModelInterface
{
    private readonly array $allowedOrderColumns;

    public function __construct()
    {
        parent::__construct();
        $this->allowedOrderColumns = ['price', 'name', 'discount_percentage', 'added_at', 'id'];
    }
    
    public function getProductGalleryImages(int $product_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product_gallery WHERE product_id = :id');
        $stmt->execute(['id' => $product_id]);
        return $stmt->fetchAll();
    }

    public function getRelatedProducts(int $product_id, int $limit = 50)
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

    public function getProductsBySubCategory(string $sub_category, int $limit = 100)
    {
        $stmt = $this->pdo->prepare('SELECT p.* FROM product p INNER JOIN sub_sub_category ssc ON p.ssc_id = ssc.id
        WHERE ssc.sub_category_id = (SELECT sc.id FROM sub_category sc WHERE sc.title = :sub_category) LIMIT :lim');
        $stmt->execute(['sub_category' => $sub_category, 'lim' => $limit]);
        return $stmt->fetchAll();
    }

    public function getProductsCount(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM product p');
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getProducts(array $filters = [], int $limit = 100, int $offset = 0, ?string $orderBy = null, bool $desc = false, int $fetch_mode = self::STRICT_FETCH_MODE): array
    {
        $query = 'SELECT p.* FROM product p';
        $params = [];

        if ($fetch_mode === self::STRICT_FETCH_MODE) {
            $op = "AND";
        } else if ($fetch_mode === self::SOFT_FETCH_MODE) {
            $op = "or";
        } else {
            throw new \Exception("Invalid fetch mode is passed: $fetch_mode");
        }

        if (!empty($filters)) {
            $query .= " WHERE 1=1";
            foreach ($filters as $fname => $fvalue) {
                if (is_array($fvalue)) {
                    $placeholders = implode(',', array_fill(0, count($fvalue), '?'));
                    $query .= " $op p.$fname IN ($placeholders)";
                    $params = array_merge($params, $fvalue);
                } elseif (strpos($fname, 'LIKE_') === 0) {
                    // Handle LIKE cases (e.g. 'LIKE_title')
                    $columnName = substr($fname, 5);
                    $query .= " $op p.$columnName LIKE ?";
                    $params[] = "%$fvalue%";
                } else {
                    $query .= " $op p.$fname = ?";
                    $params[] = $fvalue;
                }

            }
        }
        if ($orderBy && in_array($orderBy, $this->allowedOrderColumns)) {
            $query .= ' ORDER BY p.' . $orderBy . ($desc ? ' DESC' : ' ASC');
        }

        $query .= ' LIMIT ?';
        $params[] = $limit;

        if ($offset) {
            $query .= ' OFFSET ?';
            $params[] = $offset;
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return ($limit === 1) ? $stmt->fetch() : $stmt->fetchAll();
    }

    private function getFilteredProductsCount(string $query_part, array $params): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM product p" . $query_part);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getFilteredProducts(array $filters, int $limit = 100, int $offset = 0, ?string $orderBy = null, bool $desc = false): array
    {
        // key is name of group and value is array field(s) to filter
        $range_groups = ['price' => ['price', 'discount_price']];
        $params = [];
        $query = "SELECT p.* FROM product p";
        $query_part = $this->getQueryPart($filters, $range_groups, $params);
        $params[] = $limit;
        $products_count = $this->getFilteredProductsCount($query_part, $params);
        $query .= $query_part;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return array($products_count, $stmt->fetchAll());
    }

    private function getQueryPart(array $filters, array $range_groups, array &$params): string
    {
        $query_part = "";
        $conditions = "";
        $groups_count = count(array_diff_key($filters, $range_groups));
        foreach($filters as $group => $options) {
            if (array_key_exists($group, $range_groups)) {
                $min = $options[0] ?? 0;
                $max = $options[1] ?? 10_000;
                $q = join(' OR ', array_map(fn($field) => "p.$field BETWEEN ? AND ?", $range_groups[$group]));
                $conditions .= "(". $q . ") AND ";
                $params = array_merge($params, array_merge(...array_fill(0, count($range_groups[$group]), [$min, $max])));
                continue;
            }
            $placeholders = join(",", array_fill(0, count($options), '?'));
            $conditions .= "fo.alias IN ($placeholders) OR ";
            $params = array_merge($params, $options);
        }
        if ($groups_count !== 0) {
            $conditions = substr($conditions, 0, strrpos($conditions, ' OR '));
            $query_part .= " INNER JOIN filter_product fp ON p.id = fp.product_id 
                INNER JOIN filter_option fo ON fp.filter_option_id = fo.id 
                INNER JOIN filter_group fg ON fg.id = fo.filter_group_id WHERE $conditions 
                GROUP BY p.id HAVING COUNT(DISTINCT fg.alias) = $groups_count";
        } else {
            $conditions = substr($conditions, 0, strrpos($conditions, ' AND '));
            $query_part = " WHERE $conditions";
        }
        
        $query_part .= " LIMIT ?"; //
        return $query_part;
    }
}