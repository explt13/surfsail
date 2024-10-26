<?php
namespace app\controllers;

use app\models\PaginationModel;
use app\models\ProductModel;
use app\widgets\pagination\Pagination;
use nosmi\App;

class CatalogController extends AppController
{
    function indexAction()
    {
        $product_model = new ProductModel();
        $page_number = $_GET['page'] ?? 1;
        $per_page = App::$registry->getProperty("pagination");
        $pagination = new Pagination($page_number, 2, 'catalog');
        $offset = ($page_number - 1) * $per_page;
        $products = $product_model->getProducts([], 2, 0, 'added_at');
        $this->setMeta('Catalog', 'Catalog page', 'Products, list, catalog, new products, add, cart');
        $this->setData(compact('products', 'pagination'));
        $this->getView();
    }
}