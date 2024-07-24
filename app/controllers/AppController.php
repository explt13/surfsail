<?php
namespace app\controllers;

use nosmi\base\Controller;
use app\models\AppModel;

abstract class AppController extends Controller
{
    public function __construct(array $route)
    {
        parent::__construct($route);
        new AppModel();
    }
}