<?php

namespace app\widgets\pagination;

use app\models\ProductModel;
use nosmi\base\Widget;
use nosmi\App;
use nosmi\CacheInterface;

class Pagination extends Widget
{
    protected int $total_pages;
    protected int $per_page;
    protected int $current_page;
    protected ?int $next_page = null;
    protected ?int $prev_page = null;
    protected ?int $sec_next_page = null;
    protected ?int $sec_prev_page = null;
    protected string $page;
    protected bool $disabled_prev = false;
    protected bool $disabled_next = false;

    public function __construct(int $total_products, int $current_page, int $per_page, string $page)
    {
        $this->per_page = $per_page;
        $this->total_pages = ceil($total_products / $this->per_page);
        $this->page = $page;
        $this->current_page = $current_page;
        $this->tpl = __DIR__.'/tpl/pagination_tpl.php';
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
}