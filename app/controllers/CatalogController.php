<?php
namespace app\controllers;

use app\models\interfaces\FilterModelInterface;
use app\models\interfaces\ProductModelInterface;
use app\widgets\pagination\Pagination;
use nosmi\App;
use nosmi\base\Controller;

class CatalogController extends Controller
{
    protected $product_model;
    protected $filter_model;
    
    public function __construct(ProductModelInterface $product_model, FilterModelInterface $filter_model)
    {
        $this->product_model = $product_model;
        $this->filter_model = $filter_model;
    }
    
    function indexAction()
    {
        $page_number = $this->request->getQueryParam('page', 1);
        $selected_filters = $this->request->getQueryParam('f');

        $per_page = App::$registry->getProperty("pagination");
        $offset = ($page_number - 1) * $per_page;

        if (!empty($selected_filters)) {
            preg_match_all('/(?P<group>.*?):(?P<values>.*?);/', $selected_filters, $matches);
            $selected_filters = array_combine($matches['group'], array_map(fn($item) => explode(',', $item), $matches['values']));
            list($total_products, $products) = $this->product_model->getFilteredProducts($selected_filters, $per_page, $offset, 'added_at');
        } else {
            $total_products = $this->product_model->getProductsCount();
            $products = $this->product_model->getProducts(['active' => 1], $per_page, $offset, 'added_at');
        }

        $pagination = new Pagination($total_products, $page_number, $per_page, 'catalog');
        $pagination = $pagination->setup();

        if ($this->request->isAjax) {
            $html = $this->getAjaxHtml(view: 'products_ajax', data: compact('products', 'pagination', 'selected_filters'));
            echo json_encode(['html' => $html]);
            return;
        }
        $filters = $this->filter_model->getFilters();
        $this->setMeta('Catalog', 'Catalog page', 'Products, list, catalog, new products, add, cart');
        $this->render(data: compact('products', 'pagination', 'filters', 'selected_filters'));
    }
}