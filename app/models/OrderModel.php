<?php

namespace app\models;

use nosmi\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class OrderModel extends AppModel
{
    public function saveOrder()
    {
        try
        {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('INSERT INTO `order` (user_id, currency_id) VALUES (:user_id, :currency_id)');
            $stmt->execute(['user_id' => $_SESSION['user']['id'], 'currency_id' => App::$registry->getProperty('currency')['id']]);
            $order_id = (int) $this->pdo->lastInsertId();
            $this->saveOrderProduct($order_id);
            $this->emailUser();
            $this->pdo->commit();
            redirect();

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            redirect();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo "ERror has occured";
        }
    }
    protected function saveOrderProduct(int $order_id)
    {

        $product_model = new ProductModel();
        $products = $product_model->getProducts(["id" => array_keys($_SESSION['cart'])]);
        
        $data = [];
        $query = 'INSERT INTO `order_product` (order_id, product_id, title, price, qty) VALUES';
        $batchsize = 100;
        $counter = 0;
        foreach ($products as $key => $product) {
            $product_id = (int) $product['id'];
            $query .= " (?, ?, ?, ?, ?),";
            array_push($data, $order_id, $product_id, $product['title'], $product['price'], $_SESSION['cart'][$product_id]['qty']);
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

    public function emailUser()
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
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        echo 'Message has been sent';
    }
}

