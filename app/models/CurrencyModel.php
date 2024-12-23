<?php
namespace app\models;

use app\models\interfaces\CurrencyModelInterface;
use nosmi\CacheInterface;

class CurrencyModel extends AppModel implements CurrencyModelInterface
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    public function getCurrencies()
    {
        $stmt = $this->pdo->query('SELECT c.* FROM currency c ORDER BY c.base DESC');

        if ($currencies = $this->cache->get('currencies')) {
            return $currencies;
        }
        else {
            $currencies = $stmt->fetchAll();
            $curs = [];
            foreach ($currencies as $k => $v) {
                $curs[$v['code']] = $v;
            }
            $this->cache->set('currencies', $curs);
        }
        return $curs;
    }

    public static function getCurrencyByCookie($currencies)
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

    public function getCurrencyById($currency_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM currency c WHERE c.id = :id');
        $stmt->execute(['id' => $currency_id]);
        return $stmt->fetch();
    }
}