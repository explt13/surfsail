<?php
namespace app\widgets\currency;

use nosmi\base\Widget;
use nosmi\App;
use nosmi\CacheInterface;

class Currency extends Widget
{
    private array $currencies;
    private array $currency;

    public function __construct()
    {
        $this->currencies = App::$registry->getProperty('currencies');
        $this->currency = App::$registry->getProperty('currency');
        $this->tpl = require_once __DIR__.'/tpl/currency_tpl.php';
    }
}
