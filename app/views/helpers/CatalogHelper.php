<?php
namespace app\views\helpers;

use nosmi\App;

class CatalogHelper {
    
    private function __construct(){}
    
    private function __clone(){}

    public static function renderCard(array $product) {
        ?>
        <div class="slider-products__slide slide-product-card swiper-slide">
            <article class="product-card" data-id="<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8')?>">
                <?php if ($product['discount_price']): ?>
                    <div class="product-card__band product-card__band_discount"><span>- <?= htmlspecialchars($product['discount_percentage'], ENT_QUOTES, 'UTF-8');?>%</span></div>
                <?php elseif ($product['new']): ?>
                    <div class="product-card__band product-card__band_new"><span>new</span></div>
                <?php endif;?>
                <a href="product/<?= htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>" class="product-card__image-container"> <!-- CHANGE PATH -->
                    <div class="product-card__image">
                        <picture>
                            <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.webp" type="image/webp">
                            <source srcset="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.jpg" type="image/jpeg">
                            <img loading="lazy" src="img/products/<?= htmlspecialchars($product['category_alias'].'/'.$product['image'], ENT_QUOTES, 'UTF-8');?>.png" alt="<?= htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>-card-image">
                        </picture>
                    </div>
                </a>
                <div class="product-card__information information-product-card">
                    <a href="product/<?= htmlspecialchars($product['alias'], ENT_QUOTES, 'UTF-8');?>" class="information-product-card__title"><?= htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');?></a>
                    <div class="information-product-card__review review-information">
                        <div class="review-information__rating rating">
                            <div class="rating__body">
                                <div class="rating__active"></div>
                                <div class="rating__items">
                                    <input type="radio" name="product__rating" value="1" class="rating__item">
                                    <input type="radio" name="product__rating" value="2" class="rating__item">
                                    <input type="radio" name="product__rating" value="3" class="rating__item">
                                    <input type="radio" name="product__rating" value="4" class="rating__item">
                                    <input type="radio" name="product__rating" value="5" class="rating__item">
                                </div>
                            </div>
                            <div class="rating__value">4.7</div> <!-- MAKE IT FROM DB -->
                        </div>
                        <div class="review-information__review _addit-info _addit-info_light">(115)</div>
                    </div>
                    <div class="information-product-card__price">
                        <div class="product-price">
                            <?php
                            $currency = App::$registry->getProperty('currency');
                            if ($product['discount_price']): ?>
                                <span class="product-price__current">
                                    <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                    <span class="product-price__value"> <?= htmlspecialchars(number_format($product['discount_price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                </span>
                                <span class="product-price__old">
                                    <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                    <span class="product-price__value"> <?= htmlspecialchars(number_format($product['price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                </span>
                            <?php else: ?>
                                <span class="product-price__current">
                                    <span class="product-price__symbol"><?= htmlspecialchars($currency['symbol'], ENT_QUOTES, 'UTF-8');?></span>
                                    <span class="product-price__value"><?= htmlspecialchars(number_format($product['price'] * $currency['value'], 2, ',', ' '), ENT_QUOTES, 'UTF-8');?></span>
                                </span>
                            <?php endif;?>
                        </div>
                        <div class="information-product-card__actions product-card-actions">
                            <span class="_icon-comp product-card-actions__comp"></span>
                            <span class="_icon-fav product-card-actions__like"></span>
                        </div>
                    </div>
                </div>
                <button class="product-card__button cart-button button _icon-cart">To cart</button>
            </article>
        </div>
        <?php
    }

    public static function renderCatalog(array $products, string $section_title, ?string $mod = null, bool $more = true) {
        if ($products): ?>
            <section class="products <?= $mod ? 'products_' . htmlspecialchars($mod, ENT_QUOTES, 'UTF-8') : '';?>">
                <div class="products__container container">
                    <div class="products__block block-products">
                        <div class="block-products__top-row section-row-top">
                            <h2 class="block-products__title section-title"><?= htmlspecialchars($section_title, ENT_QUOTES, 'UTF-8');?></h2>
                            <?php if ($more): ?>
                                <a href="./catalog.html" class="block-products__more _icon-arrow_sh_r">All catalog</a>
                            <?php endif;?>
                        </div>
                        <div class="block-products__body body-products">
                            <div class="body-products__slider swiper slider-products">
                                <div class="swiper-wrapper">
                                    <?php foreach($products as $product){
                                        self::renderCard($product);
                                    }
                                    ?>
                                </div>
                                <div class="pagination">
                                    <div class="pagination-bullets"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif;
    }
}
?>