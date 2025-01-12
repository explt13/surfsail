<?php

namespace app\controllers;

use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use nosmi\App;

class CurrencyController extends AppController
{
    public function __construct(
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model
    )
    {
        parent::__construct($currency_model, $category_model);
    }

    public function getAction()
    {
        header('Content-Type: application/json');
        $currency = App::$registry->getProperty('currencies')[$_COOKIE['currency'] ?? 'USD'];
        if ($currency) {
            http_response_code(200);
            setcookie('currency', $currency['code'], time() + 3600 * 24 * 7, '/');
            echo json_encode(['success' => true, 'currency' => $currency]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, "message" => "No such currency exists"]);
        }

    }

    public function changeAction()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $currency = App::$registry->getProperty('currencies')[$data['currency']] ?? null;
        if ($currency) {
            http_response_code(200);
            setcookie('currency', $currency['code'], time() + 3600 * 24 * 7, '/');
            echo json_encode(['success' => true, 'message' => 'Currency successfully changed', 'currency' => $currency]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, "message" => "No such currency exists"]);
        }
    }
}