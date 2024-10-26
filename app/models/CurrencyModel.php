<?php
namespace app\models;

class CurrencyModel extends AppModel
{
    public function getCurrencies()
    {
        $stmt = $this->pdo->query('SELECT code, c.* FROM currency c ORDER BY c.base DESC');
        return $stmt->fetchAll();
    }
}