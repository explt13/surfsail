<?php
namespace app\models;

use app\models\interfaces\CurrencyModelInterface;
use Explt13\Nosmi\interfaces\CacheInterface;

class CurrencyModel extends AppModel implements CurrencyModelInterface
{
    protected CacheInterface $cache;
    protected array $currencies;

    public function __construct(CacheInterface $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    public function getCurrencies()
    {
        $currencies = $this->cache->get('currencies');

        if (!$currencies) {
            $stmt = $this->pdo->query('SELECT c.* FROM currency c ORDER BY c.base DESC');
            $curs = $stmt->fetchAll();
            $currencies = [];
            foreach ($curs as $k => $v) {
                $currencies[$v['code']] = $v;
            }
            $this->cache->set('currencies', $currencies);
        }
        $this->currencies = $currencies;
        return $currencies;
    }

    public function getCurrencyByCookie()
    {
        $key = null;
        if (empty($this->currencies)) {
            $this->getCurrencies();
        }
        if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $this->currencies)) {
            $key = $_COOKIE['currency'];
        } else {
            $key = key($this->currencies);
        }
        $currency = $this->currencies[$key];
        return $currency;
    }

    public function getCurrencyById($currency_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM currency c WHERE c.id = :id');
        $stmt->execute(['id' => $currency_id]);
        return $stmt->fetch();
    }
}