<?php

namespace app\models;

use app\models\interfaces\CartModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\IReachable;
use app\models\interfaces\OrderModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\models\interfaces\UserModelInterface;
use nosmi\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class OrderModel extends AppModel implements OrderModelInterface
{
    protected int $order_id;
    protected $product_model;
    protected $currency_model;
    protected $user_model;
    protected $cart_model;
    
    public function __construct(
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
    }
    public function saveOrder()
    {
        try
        {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('INSERT INTO `order` (user_id, currency_id) VALUES (:user_id, :currency_id)');
            $stmt->execute(['user_id' => $_SESSION['user']['id'], 'currency_id' => App::$registry->getProperty('currency')['id']]);
            $this->order_id = (int) $this->pdo->lastInsertId();
            $this->saveOrderProduct($this->order_id);
            $this->send();
            $this->pdo->commit();
            unset($_SESSION['cart']);
            redirect();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            redirect();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            redirect();
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

    protected function send()
    {
        $mail = new PHPMailer(true);

        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $_ENV['SMTP_LOGIN'];                     //SMTP username
        $mail->Password   = $_ENV['SMTP_PASSWORD'];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom($_ENV['SMTP_LOGIN'], $_ENV['APP_NAME']);
        $mail->addAddress('az13rede4d@gmail.com');     //Add a recipient
        $mail->addReplyTo($_ENV['SMTP_LOGIN']);
    
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Thanks for the purchase';
        $mail->Body    = $this->getHtml();
        $mail->AltBody = $this->getPlain();
    
        $mail->send();
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
        $user = $this->user_model->getUserByEmail($_SESSION['user']['email']);
        $currency = $this->currency_model->getCurrencyById($order['currency_id']);
        ob_start();
        require_once APP . '/views/Order/order_mail.php';
        $msg = ob_get_clean();
        return $msg;
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

