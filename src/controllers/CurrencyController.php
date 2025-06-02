<?php

namespace Surfsail\controllers;

use Explt13\Nosmi\App;
use Explt13\Nosmi\base\Controller;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;

class CurrencyController extends BaseController
{
    private CurrencyService $currency_service;
    public function __construct(
        CurrencyService $currency_service,
    )
    {
        parent::__construct();
        $this->currency_service = $currency_service;
    }

    public function get()
    {
        $currency = $this->currency_service->getCurrencyByCookie();
        if (!$currency) {
            throw new \Exception('No such currency exists', 400);

        }
        header('Content-Type: application/json');
        setcookie('currency', $currency['code'], time() + 3600 * 24 * 7, '/');
        http_response_code(200);
        echo json_encode(['success' => true, 'currency' => $currency]);

    }

    public function post()
    {
        $data = $this->request->getParsedBody();
        $currencies = $this->currency_service->getCurrencies();

        $currency = $currencies[$data['currency']] ?? null;
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