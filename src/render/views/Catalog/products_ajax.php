<div class="catalog__products">
    <?php
    use Surfsail\render\helpers\ProductHelper;
    if (empty($this->data['products'])): ?>
        <div class="catalog__no-products">No products found</div>
    <?php endif; ?>
    <?php
    foreach($this->data['products'] as $product){
        ProductHelper::renderCard($this->data['currency'], $product);
    }
    ?>
</div>
<?php if ($this->data['pagination']): ?>
<div class="catalog__footer">
    <!-- <a href="" class="catalog__more button">Show more</a> -->
    <?= $this->data['pagination']->render(); ?>
</div>
<?php endif;?>