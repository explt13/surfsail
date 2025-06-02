<?php

namespace Surfsail\controllers;

use Explt13\Nosmi\AppConfig\AppConfig;
use Explt13\Nosmi\Base\Controller;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;


/**
 * Abstract base controller class for all controllers
 * Shared logic will be placed here. 
 */
abstract class BaseController extends Controller
{
    public function __construct()
    {
    }
}