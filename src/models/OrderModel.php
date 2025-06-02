<?php

namespace Surfsail\models;

use Surfsail\interfaces\CartModelInterface;
use Surfsail\interfaces\CurrencyModelInterface;
use Surfsail\interfaces\OrderModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Surfsail\interfaces\UserModelInterface;
use Explt13\Nosmi\Interfaces\ConfigInterface;
use Explt13\Nosmi\Mail\Mail;
use PHPMailer\PHPMailer\Exception;


class OrderModel extends AppModel implements OrderModelInterface
{
    protected int $order_id;
    protected string $user_email;
    protected $product_model;
    protected $currency_model;
    protected $user_model;
    protected $cart_model;
    private ConfigInterface $config;
    
    public function __construct(
        ConfigInterface $config,
        ProductModelInterface $product_model,
        CurrencyModelInterface $currency_model,
        UserModelInterface $user_model,
        CartModelInterface $cart_model,
    )
    {
        parent::__construct();
        $this->product_model = $product_model;
        $this->currency_model = $currency_model;
        $this->user_model = $user_model;
        $this->cart_model = $cart_model;
        $this->config = $config;
    }
    public function saveOrder(array $user, array $currency)
    {
        try
        {
            $mail = new Mail($this->config);
            $this->user_email = $user['email'];
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('INSERT INTO `order` (user_id, currency_id) VALUES (:user_id, :currency_id)');
            $stmt->execute(['user_id' => $user['id'], 'currency_id' => $currency['id']]);
            $this->order_id = (int) $this->pdo->lastInsertId();
            $this->saveOrderProduct($this->order_id);
            $mail->withHtml($this->getHtml())
                       ->withAlt($this->getPlain())
                       ->withRecipient($this->user_email)
                       ->withSubject('Thanks for the purchase')
                       ->send();
            
            $this->pdo->commit();
            unset($_SESSION['cart']);
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
        }
    }

    protected function saveOrderProduct(int $order_id)
    {
        $products = $this->product_model->getProducts(["id" => array_keys($_SESSION['cart'])]);
        
        $data = [];
        $query = 'INSERT INTO `order_product` (order_id, product_id, title, price, qty) VALUES';
        $batchsize = 100;
        $counter = 0;
        foreach ($products as $key => $product) {
            $product_id = (int) $product['id'];
            $query .= " (?, ?, ?, ?, ?),";
            array_push($data, $order_id, $product_id, $product["name"], $product['price'], $_SESSION['cart'][$product_id]['qty']);
            $counter++;
            if ($counter === $batchsize) {
                $query = rtrim($query, ',');
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($data);

                $query = 'INSERT INTO `order_product` (order_id, product_id, title, price, qty) VALUES';
                $data = [];
                $counter = 0;
            }
        }
        if ($counter > 0) {
            $query = rtrim($query, ',');
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($data);
        }
    }

    public function getOrder(int $order_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `order` o WHERE o.id = :id');
        $stmt->execute(['id' => $order_id]);
        return $stmt->fetch();
    }

    private function getHtml()
    {
        $cart = $_SESSION['cart'];
        $products = $this->cart_model->getProductsFromArray($cart);
        $order = $this->getOrder($this->order_id);
        $user = $this->user_model->getUserByEmail($this->user_email);
        $currency = $this->currency_model->getCurrencyById($order['currency_id']);
        ob_start();
        require $this->config->get('APP_VIEWS') . '/Order/order_mail.php';
        return ob_get_clean();
    }
    
    private function getPlain()
    {
        $text = "Thanks for the purchase!\nOrder number: $this->order_id.\n\n";
        $num = 1;
        $total_price = 0;
        $total_product_price = 0;
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $total_product_price = $product['price'] * $product['qty'];
            $total_price += $total_product_price;
            $text .= "$num. {$product["name"]} | {$product['price']} x {$product['qty']} | $total_product_price\n";
        }
        $text .= "total: $total_price";
        return $text;
    }
}

