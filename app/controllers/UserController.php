<?php
namespace app\controllers;

class UserController extends AppController
{
    public function indexAction()
    {
        $this->getView();
    }
    
    public function settingsAction()
    {   
        $this->getView();
    }
}