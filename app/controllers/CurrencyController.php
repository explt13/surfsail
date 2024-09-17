<?php

namespace app\controllers;

use nosmi\App;

class CurrencyController extends AppController
{
    public function getAction()
    {
        header('Content-Type: application/json');
        $currency = App::$registry->getProperty('currencies')[$_COOKIE['currency'] ?? 'USD'];
        if ($currency) {
            http_response_code(200);
            echo json_encode(['success' => true, 'currency' => $currency]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false]);
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
            echo json_encode(['success' => false]);
        }
    }
}