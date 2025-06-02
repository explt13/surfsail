<?php
namespace Surfsail\controllers;

use Surfsail\interfaces\FilterModelInterface;
use Surfsail\interfaces\ProductModelInterface;
use Explt13\Nosmi\App;
use Explt13\Nosmi\base\Controller;
use Explt13\Nosmi\Interfaces\ConfigInterface;
use Surfsail\services\CategoryService;
use Surfsail\services\CurrencyService;
use Surfsail\widgets\Pagination;

class CatalogController extends BaseController
{
    private $product_model;
    private $filter_model;
    private ConfigInterface $config;
    private CurrencyService $currency_service;

    public function __construct(
        ProductModelInterface $product_model,
        FilterModelInterface $filter_model,
        ConfigInterface $config,
        CurrencyService $currency_service,
    )
    {
        parent::__construct();
        $this->product_model = $product_model;
        $this->filter_model = $filter_model;
        $this->config = $config;
        $this->currency_service = $currency_service;
    }
    
    function indexAction()
    {
        $page_number = $this->request->getQueryParams()['page'] ?? 1;
        $selected_filters = $this->request->getQueryParams()['f'] ?? [];

        $per_page = $this->config->get("APP_PAGINATION_PER_PAGE");
        $offset = ($page_number - 1) * $per_page;

        if (!empty($selected_filters)) {
            preg_match_all('/(?P<group>.*?):(?P<values>.*?);/', $selected_filters, $matches);
            $selected_filters = array_combine($matches['group'], array_map(fn($item) => explode(',', $item), $matches['values']));
            list($total_products, $products) = $this->product_model->getFilteredProducts($selected_filters, $per_page, $offset, 'added_at');
        } else {
            $total_products = $this->product_model->getProductsCount();
            $products = $this->product_model->getProducts(['active' => 1], $per_page, $offset, 'added_at');
        }

        $currency = $this->currency_service->getCurrencyByCookie();
        $pagination = new Pagination($total_products, $page_number, $per_page, 'catalog');
        $pagination = $pagination->setup();

        if ($this->request->isAjax()) {
            $html = $this->getView()->withoutLayout()->render('products_ajax', compact('products', 'pagination', 'selected_filters', 'currency'));
            $this->response = $this->response->withStatus(200)->withJson(['html' => $html, 'message' => 'Filters applied successfully']);
            return;
        } 

        $filters = $this->filter_model->getFilters();
        $html = $this->getView()->withMetaArray([
            'title' => 'Catalog',
            'description' => 'Catalog page',
            'keywords' =>'Products, list, catalog, new products, add, cart'
        ])->render('index', compact('products', 'pagination', 'filters', 'selected_filters', 'currency'));
        
        $this->response = $this->response->withStatus(200)->withHtml($html);
    }

}