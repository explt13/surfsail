<?php
namespace app\controllers;

use \app\models\interfaces\UserModelInterface;
use nosmi\base\Controller;

class UserController extends Controller
{
    protected $user_model;
    
    public function __construct(UserModelInterface $user_model)
    {
        $this->user_model = $user_model;
    }

    public function registerAction()
    {
        header('Content-Type: application/json');
        $data = $_POST['auth'];
        $result = $this->user_model->register($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function loginAction() 
    {
        $data = $_POST['auth'];
        $result = $this->user_model->login($data);
        header('Content-Type: application/json');
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function logoutAction()
    {
        $this->user_model->logout();
    }
}