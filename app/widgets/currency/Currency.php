<?php
namespace app\widgets\currency;

use app\models\CurrencyModel;
use nosmi\App;
use nosmi\Cache;

class Currency
{
    private array $currencies;
    private array $currency;
    private string $tpl;

    public function __construct()
    {
        $this->currencies = App::$registry->getProperty('currencies');
        $this->currency = App::$registry->getProperty('currency');
        $this->tpl = __DIR__.'/tpl/currency_tpl.php';
        echo $this->render();
    }
    
    public function render()
    {
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }
}
