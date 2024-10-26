<main class="page page_favorite">
    <div class="cart">
        <div class="container cart__container">
            <div class="cart__body">
                <?php
                use app\views\helpers\ProductHelper;
                if ($products): 
                    foreach ($products as $product): ?>
                        <div class="cart-item" data-id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8');?>>
                            <div class="cart-item__about">
                                <a class="cart-item__image" href="/product/<?= htmlspecialchars($product['alias'])?>">
                                    <picture>
                                        <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                                        <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.jpg" type="image/jpeg">
                                        <img src="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.png" alt="<?=htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>-image">
                                    </picture>
                                </a>
                                <div class="cart-item__information">
                                    <a class="cart-item__title" href="/product/<?= htmlspecialchars($product['alias'])?>"><?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');?></a>
                                    <div class="cart-item__description"><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');?></div>
                                </div>
                            </div>
                            <div class="cart-item__qty">
                                <div class="cart-item__price">
                                    <?php
                                        ProductHelper::renderPrice($product);
                                    ?>
                                </div>
                            </div>
                            <div class="cart-item__delete"><img src="/img/home/trash.svg" /></div>
                        </div>
                    <?php endforeach;?>
                <?php else:?>
                    <div class="cart__no-items">
                        <div class="cart__no-items-image">
                            <img src="img/home/empty-cart.svg" alt="no favorite items">
                        </div>
                        <div class="cart__no-items-text"><?= "No favorite items"; ?></div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</main>