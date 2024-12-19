<?php

namespace app\models\interfaces;

interface CurrencyModelInterface
{
    public function getCurrencies();
    public static function getCurrencyByCookie($currencies);
    public function getCurrencyById($currency_id);

}