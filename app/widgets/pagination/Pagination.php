<?php

namespace app\widgets\pagination;

use app\models\ProductModel;
use nosmi\App;

class Pagination
{
    private int $total_pages;
    private int $per_page;
    private int $current_page;
    private ?int $next_page = null;
    private ?int $prev_page = null;
    private ?int $sec_next_page = null;
    private ?int $sec_prev_page = null;
    private string $page;
    private bool $disabled_prev = false;
    private bool $disabled_next = false;

    public function __construct(int $current_page, int $per_page, string $page)
    {
        $product_model = new ProductModel;
        $total_products = $product_model->getTotalProducts();
        $this->per_page = $per_page;
        $this->total_pages = ceil($total_products / $this->per_page);
        $this->page = $page;
        $this->current_page = $current_page;
        $this->setup();
    }

    private function setup()
    {
        
        if ($this->current_page <= 1) {
            $this->current_page = 1;
            $this->disabled_prev = true;
        }
        if ($this->current_page >= $this->total_pages){
            $this->current_page = $this->total_pages;
            $this->disabled_next = true;
        }

        if ($this->current_page > 1) {
            $this->prev_page = $this->current_page - 1;
        }

        if ($this->current_page < $this->total_pages){
            $this->next_page = $this->current_page + 1;
        }
        if ($this->current_page < $this->total_pages - 1){
            $this->sec_next_page = $this->current_page + 2;
        }
        
    }

    public function render()
    {
        ob_start();
        require_once __DIR__.'/tpl/pagination_tpl.php';
        return ob_get_clean();
    }
}