<?php

namespace Surfsail\interfaces;

interface CurrencyModelInterface
{
    public function getCurrencies(): array;
    public function getCurrencyByCode(string $code): array|false;
    public function getCurrencyById($currency_id);
}