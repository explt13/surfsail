<main class="page page_cart">
    <div class="cart">
        <div class="container cart__container">
            <div class="cart__body">
                <?php
                use app\views\helpers\ProductHelper;
                foreach ($products as $product): ?>
                <div class="cart-item">
                    <div class="cart-item__about">
                        <div class="cart-item__image">
                            <picture>
                                <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                                <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.jpg" type="image/jpeg">
                                <img src="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.png" alt="<?=htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>-image">
                            </picture>
                        </div>
                        <div class="cart-item__information">
                            <div class="cart-item__title"><?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');?></div>
                            <div class="cart-item__description"><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');?></div>
                        </div>
                    </div>
                    <div class="cart-item__qty">
                        <div class="cart-item__price">
                            <?php
                            ProductHelper::renderPrice($product);
                            ?>
                        </div>
                        <div class="cart-item__quantity quantity">
                            <button class="quantity__button quantity__button_minus" type="button"></button>
                            <div class="quantity__input">
                                <input type="text" name="form[]" value="<?= htmlspecialchars($product['qty'], ENT_QUOTES, 'UTF-8');?>" autocomplete="off">
                            </div>
                            <button class="quantity__button quantity__button_plus" type="button"></button>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
        <div class="cart__footer footer-cart">
            <div class="container footer-cart__container">
                <div class="footer-cart__body">
                    <div class="footer-cart__items"><?= htmlspecialchars(count($products), ENT_QUOTES, 'UTF-8');?> items in cart</div>
                    <div class="footer-cart__sum">Total: <span><?= htmlspecialchars($currency['symbol'].' '.number_format(array_reduce($products, function($a, $b){
                        return $a + ($b['discount_price'] ?? $b['price']) * $b['qty'];
                    }, 0), 2, ',', ' ')); ?>
                    </span></div>
                    <a href="cart/buy" class="footer-cart__buy _icon-arrow_f_r">Buy</a>
                </div>
            </div>
        </div>
    </div>
</main>