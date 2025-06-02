<?php
namespace Surfsail\controllers;

use \Surfsail\interfaces\UserModelInterface;
use Explt13\Nosmi\base\Controller;

class UserController extends Controller
{
    protected $user_model;
    
    public function __construct(UserModelInterface $user_model)
    {
        $this->user_model = $user_model;
    }

    public function registerAction()
    {
        $data = $this->request->getParsedBody()['auth'];
        $result = $this->user_model->register($data);
        $this->response = $this->response
                               ->withStatus($result['response_code'])
                               ->withJson(['message' => $result['message']]);
    }
    public function loginAction() 
    {
        $data = $this->request->getParsedBody();
        $result = $this->user_model->login($data['auth']);
        $this->response = $this->response
                            ->withStatus($result['response_code'])
                            ->withJson(['message' => $result['message']]);
    }
    public function logoutAction()
    {
        $this->user_model->logout();
        $this->response = $this->response->withRedirect('/', 303);
    }
}