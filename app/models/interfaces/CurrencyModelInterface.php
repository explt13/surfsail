<?php

namespace app\models\interfaces;

interface CurrencyModelInterface
{
    public function getCurrencies();
    public function getCurrencyByCookie();
    public function getCurrencyById($currency_id);

}