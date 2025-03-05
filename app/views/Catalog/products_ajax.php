<div class="catalog__products">
    <?php
    use app\views\helpers\ProductHelper;
    if (empty($products)): ?>
        <div class="catalog__no-products">No products found</div>
    <?php endif; ?>
    <?php
    foreach($products as $product){
        ProductHelper::renderCard($product);
    }
    ?>
</div>
<?php if ($pagination): ?>
<div class="catalog__footer">
    <!-- <a href="" class="catalog__more button">Show more</a> -->
    <?php $pagination->render(); ?>
</div>
<?php endif;?>