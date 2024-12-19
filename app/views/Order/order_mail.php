<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .order-container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; }
        h2 { color: #444; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .total { font-weight: bold; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="order-container">
        <h2>Purchase information</h2>

        <table>
            <tr>
                <th>Order id:</th>
                <td><?= htmlspecialchars("#".str_pad($this->order_id, 6, '0', STR_PAD_LEFT))?></td>
            </tr>
            <tr>
                <th>Date:</th>
                <td><?= htmlspecialchars(date("m-d-Y \\a\\t H:i:s", strtotime($order['date'])));?></td>
            </tr>
        </table>

        <h3>Recipient information</h3>
        <table>
            <tr>
                <th>Recipient:</th>
                <td><?= htmlspecialchars($user['first_name'] && $user['last_name'] ? ($user['first_name'] . ' ' . $user['last_name'])  : $user['email'])?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?= htmlspecialchars($user['email'])?></td>
            </tr>
            <tr>
                <th>Address:</th>
                <td><?= htmlspecialchars($user['address'])?></td>
            </tr>
        </table>

        <h3>Product list</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php
            foreach ($products as $product_id => $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['title'])?></td>
                <td><?= htmlspecialchars($product['qty'])?></td>
                <td><?= htmlspecialchars(($product['discount_price'] ?? $product['price']) . ' ' . $currency['symbol']);?></td>
            </tr>
            <?php endforeach;?>
        </table>
        <table>
            <tr>
                <td class="total">total:</td>
                <td class="total"><?= htmlspecialchars(number_format(array_reduce($products, fn($a, $b) => $a + ($b['discount_price'] ?? $b['price']) * $b['qty'], 0), 2, ',', ' ') .' '.$currency['symbol']);?></td>
            </tr>
        </table>
    </div>
</body>
</html>
