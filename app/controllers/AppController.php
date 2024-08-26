<?php
namespace app\controllers;

use nosmi\base\Controller;
use app\models\AppModel;
use app\widgets\currency\Currency;
use nosmi\App;

abstract class AppController extends Controller
{
    public function __construct(array $route)
    {
        parent::__construct($route);
        new AppModel();
        App::$registry->setProperty('currencies', Currency::getCurrencies());
        App::$registry->setProperty('currency', Currency::getCurrency(App::$registry->getProperty('currencies')));
    }
}