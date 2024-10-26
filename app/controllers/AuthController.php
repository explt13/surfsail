<?php
namespace app\controllers;

use app\models\UserModel;

class AuthController extends AppController
{
    public function indexAction()
    {
        $this->layout = 'clean';
        $this->setMeta('Register', 'Register page');
        $this->getView();
    }

    public function signupAction()
    {
        header('Content-Type: application/json');
        $data = $_POST['auth'];
        $user_model = new UserModel();
        $result = $user_model->signUp($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function loginAction() 
    {
        header('Content-Type: application/json');
        $data = $_POST['auth'];
        $user_model = new UserModel();
        $result = $user_model->loginUser($data);
        http_response_code($result['response_code']);
        echo json_encode(['message' => $result['message']]);
    }
    public function logoutAction()
    {
        unset($_SESSION['user']);
        redirect();
    }
}