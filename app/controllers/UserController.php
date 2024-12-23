<?php
namespace app\controllers;

use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use \app\models\interfaces\UserModelInterface;

class UserController extends AppController
{
    protected $user_model;
    
    public function __construct(
        UserModelInterface $user_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($currency_model, $category_model);
        $this->user_model = $user_model;
    }

    public function authAction()
    {
        $this->setMeta('Register', 'Register page');
        $this->getView();
    }

    public function signupAction()
    {
        header('Content-Type: application/json');
        $data = $_POST['auth'];
        $result = $this->user_model->signup($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function loginAction() 
    {
        header('Content-Type: application/json');
        $data = $_POST['auth'];
        $result = $this->user_model->login($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function logoutAction()
    {
        $this->user_model->logout();
    }
}