<?php

namespace Surfsail\controllers;

use Explt13\Nosmi\base\Controller;

class AuthController extends Controller
{
    public function indexAction()
    {
        $view = $this->getView()
                     ->withLayout('clean')
                     ->withMetaArray(["title" => "Register", "description" => "User registration page"])
                     ->render($this->getRoute()->getAction());
        $this->response = $this->response->withHtml($view);
    }
}