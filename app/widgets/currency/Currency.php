<?php
namespace app\widgets\currency;

use nosmi\base\Widget;
use nosmi\App;
use nosmi\interfaces\CacheInterface;

class Currency extends Widget
{
    protected array $currencies;
    protected array $currency;

    public function __construct()
    {
        parent::__construct(null);
        $this->currencies = App::$registry->getProperty('currencies');
        $this->currency = App::$registry->getProperty('currency');
    }
}
