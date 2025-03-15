<?php
namespace app\widgets\currency;

use Explt13\Nosmi\base\Widget;
use Explt13\Nosmi\App;
use Explt13\Nosmi\interfaces\CacheInterface;

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
