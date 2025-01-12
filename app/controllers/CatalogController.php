<?php
namespace app\controllers;

use app\models\FilterModel;
use app\models\interfaces\CategoryModelInterface;
use app\models\interfaces\CurrencyModelInterface;
use app\models\interfaces\FilterModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\widgets\pagination\Pagination;
use nosmi\App;

class CatalogController extends AppController
{
    protected $product_model;
    protected $filter_model;
    
    public function __construct(
        ProductModelInterface $product_model,
        CurrencyModelInterface $currency_model,
        CategoryModelInterface $category_model,
        FilterModelInterface $filter_model,
    )
    {
        parent::__construct($currency_model, $category_model);
        $this->product_model = $product_model;
        $this->filter_model = $filter_model;
    }
    
    function indexAction()
    {
        $page_number = $_GET['page'] ?? 1;
        $per_page = App::$registry->getProperty("pagination");
        $total_products = $this->product_model->getTotalProducts();
        $offset = ($page_number - 1) * $per_page;
        $pagination = new Pagination($total_products, $page_number, $per_page, 'catalog');
        $filters = $this->filter_model->getFilters();
        $products = $this->product_model->getProducts([], $per_page, $offset, 'added_at');
        $this->setMeta('Catalog', 'Catalog page', 'Products, list, catalog, new products, add, cart');
        $this->setData(compact('products', 'pagination', 'filters'));
        $this->getView();
    }
}