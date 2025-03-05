<?php

namespace app\controllers;

use nosmi\base\Controller;

class AuthController extends Controller
{
    public function indexAction()
    {
        $this->setMeta('Register', 'Register page');
        $this->render();
    }
}