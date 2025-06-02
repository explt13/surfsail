<?php
namespace Surfsail\models;

use Surfsail\interfaces\CurrencyModelInterface;
use Explt13\Nosmi\interfaces\CacheInterface;

class CurrencyModel extends AppModel implements CurrencyModelInterface
{
    protected array $currencies;

    public function __construct()
    {
        parent::__construct();
    }

    public function getCurrencies(): array
    {
        $stmt = $this->pdo->query('SELECT c.* FROM currency c ORDER BY c.base DESC');
        return $stmt->fetchAll();

    }

    public function getCurrencyByCode(string $code): array|false
    {
        $stmt = $this->pdo->prepare('SELECT c.* FROM currency c WHERE c.name = :code');
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }

    public function getCurrencyById($currency_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM currency c WHERE c.id = :id');
        $stmt->execute(['id' => $currency_id]);
        return $stmt->fetch();
    }
}