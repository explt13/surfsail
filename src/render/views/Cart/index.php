<main class="page">
    <div class="cart">
        <div class="container cart__container">
            <div class="cart__body">
                <?php
                use Surfsail\render\helpers\ProductHelper;
                if (isset($this->data['products']) && !empty($this->data['products'])): 
                    foreach ($this->data['products'] as $product): ?>
                        <div class="cart-item" data-qty=<?= htmlspecialchars($product['available_qty']);?> data-id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8');?>>
                            <div class="cart-item__about">
                                <a class="cart-item__image" href="/product/<?= htmlspecialchars($product['alias'])?>">
                                    <picture>
                                        <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                                        <source srcset="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.jpg" type="image/jpeg">
                                        <img src="img/products/<?=htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.png" alt="<?=htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>-image">
                                    </picture>
                                </a>
                                <div class="cart-item__information">
                                    <a class="cart-item__title" href="/product/<?= htmlspecialchars($product['alias'])?>"><?= htmlspecialchars($product["name"], ENT_QUOTES, 'UTF-8');?></a>
                                    <div class="cart-item__description"><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');?></div>
                                </div>
                            </div>
                            <div class="cart-item__qty">
                                <div class="cart-item__price">
                                    <?php
                                    ProductHelper::renderPrice($this->data['currency'], $product);
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
                            <div class="cart-item__delete"><img src="/img/home/trash.svg" /></div>
                        </div>
                    <?php endforeach;?>
                <?php else:?>
                    <div class="cart__no-items">
                        <div class="cart__no-items-image">
                            <img src="img/home/empty-cart.svg" alt="no items in cart">
                        </div>
                        <div class="cart__no-items-text"><?= "No items in cart"; ?></div>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <div class="cart__footer footer-cart">
            <div class="container footer-cart__container">
                <div class="footer-cart__body">
                    <div class="footer-cart__items"><span class="footer-cart__items-qty"><?php
                    if (isset($this->data['cart_items_qty'])) {
                        echo htmlspecialchars($this->data['cart_items_qty'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo 0;
                    }
                    ?></span> items in cart
                    </div>
                    <div class="footer-cart__sum">Total:
                        <?php $currency = $this->data['currency'];?>
                        <span class="footer-cart__symbol"><?=htmlspecialchars($currency['symbol']);?> </span>
                        <span class="footer-cart__price"><?php
                        if (isset($this->data['products'])){
                            echo htmlspecialchars(number_format(array_reduce($this->data['products'], function($a, $b) use ($currency) {
                            return $a + (($b['discount_price'] ?? $b['price']) * $currency['value']) * $b['qty'];
                            }, 0), 2, ',', ' '));
                        }
                        else {
                            echo "0,00";
                        }
                        ?>
                        </span>
                    </div>
                    <?php if (isset($this->data['products'])): ?>
                        <a href="cart/buy" class="footer-cart__buy _icon-arrow_f_r _ipl-after">Buy</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</main>