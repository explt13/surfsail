<?php

namespace app\providers;

use app\providers\interfaces\ServiceProviderInterface;
use app\models\interfaces\CurrencyModelInterface;
use Explt13\Nosmi\App;

class CurrencyServiceProvider implements ServiceProviderInterface
{
    protected $currency_model;
    public function __construct(CurrencyModelInterface $currency_model)
    {
        $this->currency_model = $currency_model;
    }
    public function register()
    {
        $currencies = $this->currency_model->getCurrencies();
        $current_currency = $this->currency_model->getCurrencyByCookie();
        App::$registry->setProperty('currencies', $currencies);
        App::$registry->setProperty('currency', $current_currency);
    }
}
