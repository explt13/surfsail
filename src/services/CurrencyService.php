<?php

namespace Surfsail\services;

use Explt13\Nosmi\Interfaces\CacheFactoryInterface;
use Explt13\Nosmi\Interfaces\CacheInterface;
use Surfsail\interfaces\CurrencyModelInterface;

class CurrencyService
{
    private CurrencyModelInterface $currency_model;
    private CacheFactoryInterface $cache_factory;
    
    public function __construct(
        CurrencyModelInterface $currency_model,
        CacheFactoryInterface $cache_factory
    )
    {
        $this->currency_model = $currency_model;
        $this->cache_factory = $cache_factory;
    }

    public function getCurrencies(): array
    {
        $cache = $this->cache_factory->createCacheBasedOnConfigHandler();
        $currencies = $cache->get('currencies');

        if (is_null($currencies)) {
            $curs = $this->currency_model->getCurrencies();
            $currencies = [];
            foreach ($curs as $k => $v) {
                $currencies[$v['code']] = $v;
            }
            $cache->set('currencies', $currencies, 3600 * 24 * 7);
        }
        return $currencies;
    }

    public function getCurrencyByCookie(): array|false
    {
        $currencies = $this->getCurrencies();
        $code = 'USD';
        if (isset($_COOKIE['currency'])) {
            $code = $_COOKIE['currency'];
        }
        if (!isset($currencies[$code])) {
            return false;
        }
        $currency = $currencies[$code];
        return $currency;
    }

}