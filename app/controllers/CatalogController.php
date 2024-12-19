<?php
namespace app\controllers;

use app\models\interfaces\ProductModelInterface;
use app\widgets\pagination\Pagination;
use nosmi\App;

class CatalogController extends AppController
{
    protected $product_model;
    public function __construct(ProductModelInterface $product_model)
    {
        $this->product_model = $product_model;
    }
    
    function indexAction()
    {
        $page_number = $_GET['page'] ?? 1;
        $per_page = App::$registry->getProperty("pagination");
        $pagination = new Pagination($page_number, 2, 'catalog');
        $offset = ($page_number - 1) * $per_page;
        $products = $this->product_model->getProducts([], $per_page, $offset, 'added_at');
        $this->setMeta('Catalog', 'Catalog page', 'Products, list, catalog, new products, add, cart');
        $this->setData(compact('products', 'pagination'));
        $this->getView();
    }
}