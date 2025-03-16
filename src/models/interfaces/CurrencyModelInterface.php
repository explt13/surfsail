<?php

namespace Surfsail\models\interfaces;

interface CurrencyModelInterface
{
    public function getCurrencies();
    public function getCurrencyByCookie();
    public function getCurrencyById($currency_id);

}