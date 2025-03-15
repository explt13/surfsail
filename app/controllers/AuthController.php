<?php

namespace app\controllers;

use Explt13\Nosmi\base\Controller;

class AuthController extends Controller
{
    public function indexAction()
    {
        $this->setMeta('Register', 'Register page');
        $this->render();
    }
}