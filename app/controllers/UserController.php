<?php
namespace app\controllers;

use \app\models\interfaces\UserModelInterface;

class UserController extends AppController
{
    protected $user_model;
    
    public function __construct(UserModelInterface $user_model)
    {
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