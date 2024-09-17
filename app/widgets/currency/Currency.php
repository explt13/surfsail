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

    public static function getCurrencies()
    {
        $currency_model = new CurrencyModel();
        $cache = Cache::getInstance();
        if ($currencies = $cache->get('currencies')) {
            return $currencies;
        }
        else {
            $currencies = $currency_model->getCurrencies();
            $curs = [];
            foreach ($currencies as $k => $v) {
                $curs[$v['code']] = $v;
            }
            $cache->set('currencies', $curs);
        }
        return $curs;
        
    }
    public static function getCurrency($currencies)
    {
        $key = null;
        if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)) {
            $key = $_COOKIE['currency'];
        } else {
            $key = key($currencies);
        }
        $currency = $currencies[$key];
        return $currency;
    }
    
    public function render()
    {
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }
}
